<?php

namespace App\Http\Controllers;

use App\Models\author;
use App\Models\book_author;
use App\Models\book;
use App\Models\favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    public function index()
    {
        $author = author::select('id',author::raw("CONCAT(first_name,' ',last_name) AS name"),'bio','photo','birth' ,'death')->get();
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$author],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $value = $request->store;
        $value = json_decode($value, true);

        $validator = Validator::make($value, [
            'first_name'=>['required' , 'string' , 'min:1'],
            'last_name'=>['required' , 'string' , 'min:1'],
            'bio'=>['required', 'string'],
            'birth'=>['required', 'digits:4', 'integer', 'max:'.(date('Y'))],
            'death'=>['nullable', 'digits:4', 'integer', 'max:'.(date('Y')), 'after:birth']
        ]);
        if($validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $image_validator = Validator::make($request->all(),[
            'photo'=>'mimes:png,jpg,jpeg,bmp,gif'
        ]);
        if($image_validator->fails()){
            return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if(array_key_exists('death', $value))
            $death = $value['death'];
        else
            $death = null;

        $file_name = null;
        $photo_file = null;
        if($request->photo) {
            $file_extension = $request->photo->getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $path = 'photos/authors';
            $request->photo->move($path,$file_name);
            $photo_file = "public/photos/authors/$file_name";
        }

        $author = author::create([
            'first_name'=> $value['first_name'],
            'last_name'=> $value['last_name'],
            'bio'=> $value['bio'],
            'birth'=> $value['birth'],
            'death'=>$death,
            'photo'=> $file_name,
           ]);

        $data['author'] = $author;
        if($photo_file)
        {
            $data['photo_file'] = $photo_file;
        }

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_CREATED);
    }

    public function show(author $author)
    {
        $allBooks = [];
        $books = book_author::where('author_id', $author->id)->get();
        foreach($books as $book){
            array_push($allBooks,book::where('id', $book->book_id)->select('id','name', 'cover')->first());
        }
        $like = favorite::where('user_id', Auth::id())->where('author_id', $author->id)->first();
        if($author->death)
            $age = $author->death - $author->birth;
        else
            $age = date('Y') - $author->birth;
        $data['author'] = ['id'=>$author->id, 'name'=>$author->first_name.' '.$author->last_name, 'bio'=>$author->bio, 'birth'=>$author->birth,'death'=>$author->death, 'age'=>$age, 'photo'=>$author->photo];
        $data['allBooks'] = $allBooks;
        if($like)
            $data['like'] = 1;
        else
            $data['like'] = 0;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        if($res = $request->name)
        {
            $data = [];
            $authors = Author::Where(Author::raw("CONCAT(first_name, ' ', last_name)"),'LIKE','%'.$res.'%')
            ->select('id' , Author::raw("CONCAT(first_name, ' ', last_name) AS name") , 'photo')
            ->get();
            foreach($authors as $author)
            {
                $number = book_author::where('author_id',$author->id)->count('book_id');
                array_push($data, ['name'=>$author->name, 'photo'=>$author->photo, 'number of books'=>$number]);
            }
            return response()->json(['status'=> 200 ,'message'=>'successful', 'data'=>$data],Response::HTTP_OK);
        }
        else
            return  response()->json(['status'=>204,'message'=>'no input', 'data'=>null], Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request, author $author)
    {
        $validator = Validator::make($request->all(), [
            'first_name'=>['nullable' , 'string' , 'min:1'],
            'last_name'=>['nullable' , 'string' , 'min:1'],
            'bio'=>['nullable', 'string'],
            'birth'=>['nullable', 'digits:4', 'integer', 'max:'.(date('Y'))],
            'death'=>['nullable', 'digits:4', 'integer', 'max:'.(date('Y'))]
        ]);
        if($validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('first_name')){
            $first_name = $request->input('first_name');
            $author->update([
               'first_name'=> $first_name,
            ]);
        }

        if($request->has('last_name')){
            $last_name = $request->input('last_name');
            $author->update([
               'last_name'=> $last_name,
            ]);
        }

        if($request->has('bio')){
            $bio = $request->input('bio');
            $author->update([
               'bio'=> $bio,
            ]);
        }

        if($request->has('birth')){
            $birth = $request->input('birth');
            $author->update([
               'birth'=> $birth,
            ]);
        }

        if($request->has('death')){
            $death = $request->input('death');
            $author->update([
                'death'=> $death,
            ]);
        }

        $photo_file = null;
        if($request->photo) {
            $image_validator = Validator::make($request->all(), [
                'photo'=>'mimes:png,jpg,jpeg,bmp,gif'
            ]);
            if($image_validator->fails()){
                return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }

            $file_extension = $request->photo->getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $path = 'photos/authors';
            $request->photo->move($path,$file_name);

            $author->update([
                'photo'=>$file_name,
            ]);

            $photo_file = "public/photos/authors/$file_name";
        }

        $data['author'] = $author;
        if($photo_file)
        {
            $data['photo_file'] = $photo_file;
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function destroy(author $author)
    {
        $author->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$author],Response::HTTP_NO_CONTENT);
    }
}
