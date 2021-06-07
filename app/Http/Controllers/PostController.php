<?php

namespace App\Http\Controllers;

use App\Models\datakantin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
    * store
    *
    * @param  mixed $request
    * @return void
    */
    public function index()
    {
        $data = datakantin::latest()->when(request()->search, function($datakantin) {
            $datakantin = $datakantins->where('namakantin', 'like', '%'. request()->search . '%');
        })->paginate(5);

        return view('post.index', compact('posts'));
    }
    /**
    * store
    *
    * @param  mixed $request
    * @return void
    */
    public function create()
    {
        return view('post.create');
    } 
    /**
    * store
    *
    * @param  mixed $request
    * @return void
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'namakantin'     => 'required',
            'alamat'   => 'required',
            'kontak' => 'required'
        ]);

        $post = datakantin::create([
            'namakantin'    => $request->namakantin,
            'alamat'   => $request->alamat,
            'kontak' => $request->kontak
        ]);

        if($post){
            //redirect dengan pesan sukses
            return redirect()->route('post.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('post.index')->with(['error' => 'Data Gagal Disimpan!']);
        }

    }
    /**
    * store
    *
    * @param  mixed $request
    * @return void
    */
    public function edit(datakantin $post)
    {
        return view('post.edit', compact('post'));
    }
        
    /**
    * update
    *
    * @param  mixed $request
    * @param  mixed $post
    * @return void
    */
    public function update(Request $request, datakantin $post)
    {
        $this->validate($request, [
                'namakantin'   => 'required',
                'alamat'   => 'required',
                'kontak' => 'required'
        ]);
            
        //get data post by ID
        $post = datakantin::findOrFail($post->id);
            
        if($request->file('image') == "") {
            
            $post->update([
                'namakantin'    => $request->namakantin,
                'alamat'   => $request->alamat,
                'kontak' => $request->kontak
            ]);
            
        } else {
            
            //hapus old image
            Storage::disk('local')->delete('public/datakantin/'.$post->image);
            
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/datakantin', $image->hashName());
            
            $post->update([
                'namakantin'    => $request->namakantin,
                'alamat'   => $request->alamat,
                'kontak' => $request->kontak
            ]);
            
        }
            
        if($post){
            //redirect dengan pesan sukses
            return redirect()->route('post.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('post.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }
    /**
    * store
    *
    * @param  mixed $request
    * @return void
    */
    public function destroy($id)
    {
        $post = datakantin::findOrFail($id);
        Storage::disk('local')->delete('public/datakantin/'.$post->image);
        $post->delete();

        if($post){
            //redirect dengan pesan sukses
            return redirect()->route('post.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('post.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
