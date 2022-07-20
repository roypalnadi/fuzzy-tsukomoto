<?php

namespace App\Http\Controllers;

use App\Models\TbKeputusan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $model = TbKeputusan::where('id', $request->id)->first();

        $dataKeputusan = TbKeputusan::orderBy('id')->get();

        return view('dashboard', compact('dataKeputusan', 'model'));
    }

    public function save(Request $request)
    {
        if ($request->id) {
            $model = TbKeputusan::where('id', $request->id)->first();
        } else {
            $model = new TbKeputusan();
        }

        $model->fill($request->all());
        $model->hasil = FuzzyController::init($request->psikologi, $request->tkk, $request->jasmani, $request->akademik, $request->kuota);
        // $model->hasil = dd(FuzzyController::init(1, 1, 1, 1, 1));
        $model->save();

        return redirect('home');
    }

    public function delete(Request $request)
    {
        $model = TbKeputusan::where('id', $request->id)->first();
        $model->delete();

        return redirect()->back();
    }
}
