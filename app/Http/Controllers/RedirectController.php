<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRedirectRequest;
use App\Models\RedirectLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Database\QueryException;
use App\Models\Redirect;

class RedirectController extends Controller
{
    public function index()
    {
        $redirects = Redirect::all();
        return view('redirects.index', ['redirects' => $redirects]);
    }

    public function create()
    {
        return view('redirects.create');
    }

    public function store(CreateRedirectRequest $request)
    {
        $data = $request->validated();

        // Gerar um código único para o novo Redirect
        $hashids = new Hashids(config('app.key'), 8);
        $code = $hashids->encode(time()); // Usei o timestamp para garantir a unicidade

        $redirect = Redirect::create([
            'url_destino' => $data['url_destino'],
            'status' => true,
            'code' => $code,
        ]);

        return redirect()->route('redirects.index')->with('success', 'Redirect criado com sucesso!');
    }

    public function edit($code)
    {
        // Encontre o Redirect pelo código
        $redirect = Redirect::where('code', $code)->first();

        if (!$redirect) {
            return redirect()->route('redirects.index')->with('error', 'Redirect não encontrado!');
        }

        return view('redirects.edit', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect)
    {
        $validator = Validator::make($request->all(), [
            'url_destino' => 'required|url|starts_with:https',
            'status' => 'required|in:0,1', // Validação para garantir que o status seja 0 ou 1
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $redirect->update([
            'url_destino' => $request->input('url_destino'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('redirects.index')->with('success', 'Redirect atualizado com sucesso!');
    }

    public function toggle(Redirect $redirect)
    {
        $redirect->update(['ativo' => !$redirect->ativo]);
        return redirect()->route('redirects.index');
    }

    public function destroy($code)
    {
        // Encontre o Redirect pelo código
        $redirect = Redirect::where('code', $code)->first();

        if (!$redirect) {
            return redirect()->route('redirects.index')->with('error', 'Redirect não encontrado!');
        }

        try {
            $redirect->delete();
            return redirect()->route('redirects.index')->with('success', 'Redirect deletado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Error deleting Redirect: ' . $e->getMessage());
            return redirect()->route('redirects.index')->with('error', 'Erro ao deletar o Redirect!');
        }
    }




    public function redirect(Request $request, $code)
    {
        // Recupere o Redirect pelo Code
        $redirect = Redirect::where('code', $code)->first();

        if (!$redirect) {
            abort(404);
        }

        // Registro de acesso no RedirectLog
        $queryParams = $request->except('_token');

        $redirectLogData = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'query_params' => json_encode($queryParams),
            'redirect_id' => $redirect->id,
        ];

        try {
            // Criando o RedirectLog com os dados corretos
            $redirectLog = new RedirectLog($redirectLogData);
            $redirectLog->save();
        } catch (\Exception $e) {
            \Log::error('Error creating RedirectLog: ' . $e->getMessage());
            throw $e; // Throw the exception to see the error in the response
        }

        // Redirecionamento
        $urlDestino = $redirect->url_destino;

        if (!empty($queryParams)) {
            $urlDestino .= '?' . http_build_query($queryParams);
        }

        return redirect()->away($urlDestino);
    }

    public function stats($redirectId)
    {
        $redirect = Redirect::findOrFail($redirectId);

        $totalAccesses = $redirect->redirectLogs()->count();


        $uniqueAccesses = $redirect->redirectLogs()->distinct('ip')->count('ip');

        $topReferrers = $redirect->redirectLogs()
            ->select('referer')
            ->groupBy('referer')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get();

        $accessesLast10Days = $redirect->redirectLogs()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total, COUNT(DISTINCT ip) as unique_accesses')
            ->where('created_at', '>', now()->subDays(10))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('redirects.stats', [
            'redirect' => $redirect,
            'totalAccesses' => $totalAccesses,
            'uniqueAccesses' => $uniqueAccesses,
            'topReferrers' => $topReferrers,
            'accessesLast10Days' => $accessesLast10Days,
        ]);
    }
}
