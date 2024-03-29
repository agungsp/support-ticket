<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Priority;
use App\Project;
use App\Status;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\FunctionHelper;
use App\Helpers\TicketHelper;
use App\WorkingLog;
use Illuminate\Validation\Rule;

class TicketsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        $user_role = Auth::user()->roles()->first()->id;
        if ($request->ajax()) {
            if($user_role == 1 || $user_role == 2){
                $query = Ticket::with(['status', 'priority', 'category', 'assigned_to_user', 'comments.media', 'project', 'media'])
                               ->filterTickets($request)
                               ->select(sprintf('%s.*', (new Ticket)->table))
                               ->orderBy('status_id', 'asc');
            } else {
                $query = Ticket::with(['status', 'priority', 'category', 'assigned_to_user', 'comments.media', 'project', 'media'])
                               ->filterTickets($request)
                               ->select(sprintf('%s.*', (new Ticket)->table))
                               ->where('author_name', Auth::user()->name)
                               ->orderBy('status_id', 'asc');
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_show';
                $editGate      = 'ticket_edit';
                $deleteGate    = 'ticket_delete';
                $crudRoutePart = 'tickets';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('created_at', function ($row) {
                return $row->id ? $row->created_at : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('priority_name', function ($row) {
                return $row->priority ? $row->priority->name : '';
            });
            $table->addColumn('priority_color', function ($row) {
                return $row->priority ? $row->priority->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('author_name', function ($row) {
                return $row->author_name ? $row->author_name : "";
            });
            $table->editColumn('author_email', function ($row) {
                return $row->author_email ? $row->author_email : "";
            });
            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });
            $table->addColumn('assigned_to_user_name', function ($row) {
                return $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });

            $table->addColumn('comments_count', function ($row) {
                return $row->comments->count();
            });

            $table->addColumn('attachment_count', function ($row) {
                return $row->attachments->count();
            });

            $table->addColumn('last_comment', function ($row) {
                return $row->comments->last()->comment_text ?? '-';
            });

            $table->addColumn('view_link', function ($row) {
                return route('admin.tickets.show', $row->id);
            });

            $table->editColumn('work_duration', function ($row) {
                return floor($row->work_duration/3600) . ' jam ' . floor(($row->work_duration/60)%60) . ' menit';
            });

            $table->addColumn('attachments', function ($row) {
                return $row->attachments->count();
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'priority', 'category', 'assigned_to_user', 'project']);

            return $table->make(true);
        }

        $priorities = Priority::all();
        $statuses = Status::all();
        $categories = Category::all();

        // dd(Auth::user()->projects->first());
        return view('admin.tickets.index', compact('priorities', 'statuses', 'categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_to_users = User::whereDoesntHave('roles', function($query) {
                $query->whereId(3);
            })
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');;

        return view('admin.tickets.create', compact('statuses', 'priorities', 'categories', 'assigned_to_users', 'projects'));
    }

    public function store(StoreTicketRequest $request, Ticket $ticket)
    {
        $user_role = Auth::user()->roles()->first()->id;
        $project = Auth::user()->projects->first() ?? null;

        if(!is_null($project) || $user_role == 1){
            if ($user_role == 1) {
                $code = $this->getCodeWithId($request->project_id);
                $ticket = Ticket::create([
                    'title' => $request->title,
                    'code' => $code,
                    'content' => $request->content,
                    'author_name' => Auth::user()->name,
                    'author_email' => Auth::user()->email,
                    'status_id' => $request->status_id ?? 1,
                    'priority_id' => $request->priority_id ?? 1,
                    'category_id' => $request->category_id ?? 1,
                    'assigned_to_user_id' => $request->assigned_to_user_id,
                    'project_id' => $request->project_id
                ]);
                $ticket->project()->associate($request->project_id);
                $ticket->save();
            } else {
                $code = $this->getCode($project);
                $assign_pm = Project::find($project->id)->users()->where('is_pm', true)->first()->pivot->user_id ?? 0;
                $ticket = Ticket::create([
                    'title' => $request->title,
                    'code' => $code,
                    'content' => $request->content,
                    'author_name' => Auth::user()->name,
                    'author_email' => Auth::user()->email,
                    'status_id' => $request->status_id ?? 1,
                    'priority_id' => $request->priority_id ?? 1,
                    'category_id' => $request->category_id ?? 1,
                    'assigned_to_user_id' => $request->assigned_to_user_id ?? $assign_pm,
                    'project_id' => $request->project_id ?? $project->id
                ]);
                $ticket->project()->associate($project);
                $ticket->save();
            }

            foreach ($request->input('attachments', []) as $file) {
                $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }

        } else {
            return redirect()->back()->withStatus('Tiket anda gagal ditambahkan. Silahkan hubungi Admin kami untuk info lebih lanjut');
        }

        return redirect()->route('admin.tickets.index')->withStatus('Tiket anda telah berhasil ditambahkan. Silahkan tunggu hingga mendapatkan balasan melalui email dari kami');
    }

    public function edit(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_to_users = User::whereDoesntHave('roles', function($query) {
                $query->whereId(3);
            })
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $ticket->load('status', 'priority', 'category', 'assigned_to_user');

        return view('admin.tickets.edit', compact('statuses', 'priorities', 'categories', 'assigned_to_users', 'ticket'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $recreateLog = true;
        if ($ticket->assigned_to_user->id != auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()
                             ->withStatus('Anda tidak bisa mengedit tiket ini. Silahkan hubungi Admin kami untuk info lebih lanjut');
        }

        $request->validate([
            "title" => ['required', 'string'],
            "content" => ['required', 'string'],
            "status_id" => ['required'],
            "priority_id" => ['required'],
            "category_id" => ['required'],
            "assigned_to_user_id" => ['required'],
            "work_start" => [
                Rule::requiredIf(function () use ($request) {
                    return $request->has('checkbox_edit_work_start');
                })
            ],
            "work_start_reason" => [
                Rule::requiredIf(function () use ($request) {
                    return $request->has('checkbox_edit_work_start');
                })
            ],
            "work_end" => [
                Rule::requiredIf(function () use ($request) {
                    return $request->has('checkbox_edit_work_end');
                })
            ],
            "work_end_reason" => [
                Rule::requiredIf(function () use ($request) {
                    return $request->has('checkbox_edit_work_end');
                })
            ]
        ]);

        if (!$request->has('checkbox_edit_work_start')) {
            $request->request->remove('old_work_start');
            $request->request->remove('work_start');
            $request->request->remove('work_start_reason');
            $recreateLog = false;
        }

        if (!$request->has('checkbox_edit_work_end')) {
            $request->request->remove('old_work_end');
            $request->request->remove('work_end');
            $request->request->remove('work_end_reason');
            $recreateLog = false;
        }

        if ($recreateLog) {
            Ticket::withoutEvents(function () use ($ticket, $request) {
                $ticket->update($request->all());
            });
        }
        else {
            $ticket->update($request->all());
        }

        if (count($ticket->attachments) > 0) {
            foreach ($ticket->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $ticket->attachments->pluck('file_name')->toArray();

        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }

        if (!empty($request->status_id)) {
            $ticket->status_id = $request->status_id;

            if ($request->status_id == 3 && empty($ticket->work_start)) {
                $ticket->work_start = now();
            }

            if ($request->status_id == 5 && !empty($ticket->work_start) && empty($ticket->work_end)) {
                $ticket->work_end = now();
            }

            $ticket->save();
            $ticket->refresh();
            TicketHelper::generateWorkingLog($ticket->id);
        }

        if ($recreateLog) {
            TicketHelper::recreateLog(collect([$ticket]));
        }

        TicketHelper::calculateWorkDuration(collect([$ticket]), $recreateLog);

        return redirect()->route('admin.tickets.index')->with('status', 'Perubahan berhasil ditambahkan.');
    }

    public function show(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $project = Auth::user()->projects->first() ?? null;

        $ticket->load('status', 'priority', 'category', 'assigned_to_user', 'ref');

        $statuses = Status::all();
        $priorities = Priority::all();
        $categories = Category::all();
        $ticketRef = Ticket::whereNotIn('id', [$ticket->id])->where('project_id', $ticket->project_id)->get();
        return view('admin.tickets.show', compact('ticket', 'statuses', 'priorities', 'categories', 'ticketRef'));
    }

    public function destroy(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketRequest $request)
    {
        Ticket::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment_text' => 'required'
        ]);
        $user = auth()->user();
        $comment = $ticket->comments()->create([
            'author_name'   => $user->name,
            'author_email'  => $user->email,
            'user_id'       => $user->id,
            'comment_text'  => $request->comment_text
        ]);

        foreach ($request->input('attachments', []) as $file) {
            $comment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        if (!empty($request->status_comment)) {
            $ticket->status_id = $request->status_comment;

            if ($request->status_comment == 3 && empty($ticket->work_start)) {
                $ticket->work_start = now();
            }

            if ($request->status_comment == 5 && !empty($ticket->work_start) && empty($ticket->work_end)) {
                $ticket->work_end = now();
            }

            $ticket->save();
            $ticket->refresh();
            TicketHelper::generateWorkingLog($ticket->id);
        }

        TicketHelper::calculateWorkDuration(collect([$ticket]));

        $ticket->sendCommentNotification($comment);

        return redirect()->back()->withStatus('Komentar anda berhasil ditambahkan');
    }

    /**
     * Generate New Ticket Code
     *
     * @param \App\Project $project Project
     * @return string
     **/
    private function getCode($project)
    {
        $lastCode = $project->tickets()
                            ->whereYear('created_at', now()->year)
                            ->latest()
                            ->first();
        $newNum = empty($lastCode) ? 1 : intval(explode('.', $lastCode->code)[2]) + 1;
        return $project->code . '.' . now()->format('my') . '.' . Str::padLeft($newNum, 4, '0');
    }

    private function getCodeWithId($project)
    {
        $lastCode = Ticket::where('project_id', $project)
                            ->whereYear('created_at', now()->year)
                            ->latest()
                            ->first();
        $project_code = explode('.', $lastCode->code);
        $newNum = empty($lastCode) ? 1 : intval($project_code[2]) + 1;
        return $project_code[0] . '.' . now()->format('my') . '.' . Str::padLeft($newNum, 4, '0');
    }

    public function getComments(Request $request)
    {
        $comments = Comment::with(['media', 'user'])
                           ->where('ticket_id', $request->id)
                           ->get();
        return view('partials.commentTicketList', compact('comments'))->render();
    }

    public function showReport()
    {
        return view('admin.tickets.report');
    }

    public function getReport(Request $request)
    {
        // $awal = Carbon::create($request->awal);
        // $akhir = Carbon::create($request->akhir)->addDay();
        // $tickets = Ticket::with('project', 'status', 'category', 'priority')
        //                  ->whereBetween('created_at', [$awal, $akhir])
        //                  ->when(!auth()->user()->isAdmin(), function ($query) {
        //                      return $query->whereHas('project', function ($q) {
        //                                 $q->where('project_id', auth()->user()->projects->first()->id ?? 0);
        //                     });
        //                  })
        //                  ->get([
        //                     'tickets.created_at as tgl',
        //                     'tickets.title as judul',
        //                     'tickets.content as deskripsi',
        //                     'tickets.author_name as author',
        //                     'projects.name as proyek',
        //                     'users.name as PIC',
        //                     'categories.name as kategori',
        //                     'priorities.name as prioritas',
        //                     'statuses.name as status',
        //                     'tickets.work_duration as work_duration'
        //                  ]);
        //     dd($tickets);




        $awal = $request->awal . " 00:00:00" ?? '';
        $akhir = $request->akhir . " 23:59:59" ?? '';
        $user_role = Auth::user()->roles()->first()->id;
        if($user_role == 1){
            $data = DB::table('tickets')
                ->join('projects', 'tickets.project_id', '=', 'projects.id')
                ->join('users', 'tickets.assigned_to_user_id', '=', 'users.id')
                ->join('statuses', 'tickets.status_id', '=', 'statuses.id')
                ->join('categories', 'tickets.category_id', '=', 'categories.id')
                ->join('priorities', 'tickets.priority_id', '=', 'priorities.id')
                ->select(
                    'tickets.created_at as tgl',
                    'tickets.title as judul',
                    'tickets.content as deskripsi',
                    'tickets.author_name as author',
                    'projects.name as proyek',
                    'users.name as PIC',
                    'categories.name as kategori',
                    'priorities.name as prioritas',
                    'statuses.name as status',
                    'tickets.work_duration as work_duration'
                )
                ->whereBetween('tickets.created_at', [$awal, $akhir])
                ->get();

            $result = collect($data)->map(function ($item) {
                $item->menit = FunctionHelper::addMinuteColumn($item->work_duration);
                $item->work_duration = gmdate('H \j\a\m i \m\e\n\i\t', $item->work_duration);
                return $item;
            });
        } else {
            $project = Auth::user()->projects->first()->id ?? null;
            if (!is_null($project)) {
                $data = DB::table('tickets')
                ->join('projects', 'tickets.project_id', '=', 'projects.id')
                ->join('users', 'tickets.assigned_to_user_id', '=', 'users.id')
                ->join('statuses', 'tickets.status_id', '=', 'statuses.id')
                ->join('categories', 'tickets.category_id', '=', 'categories.id')
                ->join('priorities', 'tickets.priority_id', '=', 'priorities.id')
                ->select(
                    'tickets.created_at as tgl',
                    'tickets.title as judul',
                    'tickets.content as deskripsi',
                    'tickets.author_name as author',
                    'projects.name as proyek',
                    'users.name as PIC',
                    'categories.name as kategori',
                    'priorities.name as prioritas',
                    'statuses.name as status',
                    'tickets.work_duration as work_duration'
                )
                ->where('tickets.project_id', $project)
                ->whereBetween('tickets.created_at', [$awal, $akhir])
                ->get();

                $result = collect($data)->map(function ($item) {
                    $item->menit = FunctionHelper::addMinuteColumn($item->work_duration);
                    $item->work_duration = gmdate('H \j\a\m i \m\e\n\i\t', $item->work_duration);
                    return $item;
                });
            } else {
                $result = [];
            }
        }

        // dd($request);
        // dd(collect($result));
        return collect($result);
    }

    public function quickEdit(Request $request, $ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket->assigned_to_user->id != auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()
                             ->withStatus('Anda tidak bisa mengedit tiket ini. Silahkan hubungi Admin kami untuk info lebih lanjut');
        }

        $ticket->update($request->all());

        if (!empty($request->status_id)) {
            $ticket->status_id = $request->status_id;

            if ($request->status_id == 3 && empty($ticket->work_start)) {
                $ticket->work_start = now();
            }

            if ($request->status_id == 5 && !empty($ticket->work_start) && empty($ticket->work_end)) {
                $ticket->work_end = now();
            }

            $ticket->save();
            $ticket->refresh();
            TicketHelper::generateWorkingLog($ticket->id);
        }

        TicketHelper::calculateWorkDuration(collect([$ticket]));

        return redirect()->route('admin.tickets.index')->with('status', 'Perubahan berhasil ditambahkan.');
    }
}
