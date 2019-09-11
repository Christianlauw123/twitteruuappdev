<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JenisMobil;
use App\Bbm;
use DB;
use Validator;

class JenisMobilController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [["link" => "", "nama" => "Master Data"]];
        $jenis_mobils = JenisMobil::where("is_active", "1")->get();
        $bbms = Bbm::where("is_active", "1")->get();
        return view("jenis_mobil.index", compact("jenis_mobils", "bbms", "breadcrumbs"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            // validate data
            $arr_validator = [
                "nama" => "required|max:50",
                "insentif" => "required|integer|min:1",
                "km_l" => "required|numeric|min:1",
                "bbm_id" => "required|exists:bbms,id"
            ];

            $arr_messages = [
                "required" => "Data :attribute harus diisi",
                "exists" => "Data :attribute tidak ditemukan",
                "max" => "Data :attribute terlalu panjang",
                "min" => "Data :attribute terlalu sedikit",
                "integer" => "Data :attribute harus berupa angka",
                "numeric" => "Data :attribute harus berupa angka"
            ];

            $validator = Validator::make($request->all(), $arr_validator, $arr_messages);

            // if data not valid
            if($validator->fails())
            {
                return response()->json(["success" => 0, "errors" => $validator->getMessageBag()->toArray()]);
            }

            // data valid
            // insert to db
            $jenis_mobil = new JenisMobil;
            $jenis_mobil->nama = $request->get("nama");
            $jenis_mobil->insentif = $request->get("insentif");
            $jenis_mobil->km_l = $request->get("km_l");
            $jenis_mobil->bbm_id = $request->get("bbm_id");
            $jenis_mobil->save();

            $request->session()->flash("status", ["message" => "Data jenis mobil berhasil ditambahkan", "type" => "success"]);
            DB::commit();
            return response()->json(["success" => true]);
        } catch(Exception $e){
            DB::rollBack();
            // session()->flash("status", ["message" => "Terjadi kesalahan sistem", "type" => "danger"]);
            // return response()->json([
            //     'success'=>1,
            // ]);
            return response()->json(["success" => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jenis_mobil = JenisMobil::find($id);
        if($jenis_mobil == null)
        {
            return response()->json(["success" => false, "message" => "Jenis Mobil tidak ditemukan"]);
        }
        return response()->json(["success" => true, "data" => $jenis_mobil]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            // validate data
            $arr_validator = [
                "nama" => "required|max:50",
                "insentif" => "required|integer|min:1",
                "km_l" => "required|numeric|min:1",
                "bbm_id" => "required|exists:bbms,id"
            ];

            $arr_messages = [
                "required" => "Data :attribute harus diisi",
                "exists" => "Data :attribute tidak ditemukan",
                "max" => "Data :attribute terlalu panjang",
                "min" => "Data :attribute terlalu sedikit",
                "integer" => "Data :attribute harus berupa angka",
                "numeric" => "Data :attribute harus berupa angka"
            ];

            $validator = Validator::make($request->all(), $arr_validator, $arr_messages);

            // if data not valid
            if($validator->fails())
            {
                return response()->json(["success" => 0, "errors" => $validator->getMessageBag()->toArray()]);
            }

            // data valid
            // insert to db
            $jenis_mobil = JenisMobil::find($id);
            if($jenis_mobil == null)
            {
                abort(404);
            }
            $jenis_mobil->nama = $request->get("nama");
            $jenis_mobil->insentif = $request->get("insentif");
            $jenis_mobil->km_l = $request->get("km_l");
            $jenis_mobil->bbm_id = $request->get("bbm_id");
            $jenis_mobil->save();

            $request->session()->flash("status", ["message" => "Data jenis mobil berhasil ditambahkan", "type" => "success"]);
            DB::commit();
            return response()->json(["success" => true]);
        } catch(Exception $e){
            DB::rollBack();
            // session()->flash("status", ["message" => "Terjadi kesalahan sistem", "type" => "danger"]);
            // return response()->json([
            //     'success'=>1,
            // ]);
            return response()->json(["success" => false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenis_mobil = JenisMobil::find($id);
        if($jenis_mobil == null)
        {
            abort(404);
        }
        $jenis_mobil->is_active = 0;
        $jenis_mobil->save();

        session()->flash("status", ["message" => "Data jenis mobil berhasil dihapus", "type" => "success"]);
        return response()->json(["success" => true]);
    }
}
