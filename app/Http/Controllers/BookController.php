<?php

namespace App\Http\Controllers;

use App\Models\book;
use App\Models\author;
use App\Models\user;
use App\Models\book_author;
use App\Models\discount;
use App\Models\rate;
use App\Models\category;
use App\Models\category_book;
use App\Models\amateure_writer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $book = Book::where('amateur',0)->get();
        $data = [];
        foreach($book as $b)
        {
            array_push($data , ['book'=>$b ,
                                'new_price'=>BookController::newPrice($b),
                                "rate"=>BookController::showRate($b)]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $info = $request->info;
        $info = json_decode($info, true);

       $Validator = Validator::make($info,[
            'name'=>['required','string' , 'min:1'],
            'description'=>['required','string'],
            'page_number'=>['required' , 'numeric', 'min:0'],
            'publishing_house'=>['required','string'],
            'copies_number'=>['required', 'numeric','min:0'],
            'price'=>['required', 'numeric','min:0'],
            'classification'=>['nullable' , 'string'],
            'publishing_year'=>['required' , 'date'],
            'state'=>['required','boolean']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $image_validator = Validator::make($request->all(),[
            'cover'=>'required|mimes:png,jpg,jpeg,bmp,gif'
        ]);
        if($image_validator->fails()){
            return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $pdf_validator = Validator::make($request->all(), [
            'PDF'=>'mimes:pdf'
        ]);
        if($pdf_validator->fails()){
            return response()->json(['status' => 400 ,'message' => $pdf_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $audio_validator = Validator::make($request->all(), [
            'audio_book'=>'mimes:mp3,wav,ogg'
        ]);
        if($audio_validator->fails()){
            return response()->json(['status' => 400 ,'message' => $audio_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if(array_key_exists('classification', $info))
            $classification = $info['classification'];
        else
            $classification = null;

        $file_extension = $request->cover->getClientOriginalExtension();
        $file_name = time().'.'.$file_extension;
        $path = 'photos/books';
        $request->cover->move($path,$file_name);
        $photo_file = "public/photos/books/$file_name";

        $file_name2 = null;
        $pdf_file = null;
        if($request->PDF)
        {
            $file_extension2 = $request->PDF->getClientOriginalExtension();
            $file_name2 = time().'.'.$file_extension2;
            $path2 = 'files/pdf';
            $request->PDF->move($path2, $file_name2);
            $pdf_file = "public/files/pdf/$file_name2";
        }

        $file_name3 = null;
        $audio_file = null;
        if($request->audio_book)
        {
            $file_extension3 = $request->audio_book->getClientOriginalExtension();
            $file_name3 = time().'.'.$file_extension3;
            $path3 = 'files/audio';
            $request->audio_book->move($path3, $file_name3);
            $audio_file = "public/files/audio/$file_name3";
        }

        $book = Book::create([
            'name'=> $info['name'],
            'description'=>$info['description'],
            'page_number'=>$info['page_number'],
            'publishing_house'=>$info['publishing_house'],
            'publishing_year'=>$info['publishing_year'],
            'copies_number'=>$info['copies_number'],
            'price'=>$info['price'],
            'cover'=>$file_name,
            'classification'=>$classification,
            'state'=>$info['state'],
            'PDF'=>$file_name2,
            'audio_book'=>$file_name3,
         ]);

        $data['book'] = $book;
        $data['photo_file'] = $photo_file;
        if($pdf_file)
            $data['pdf_file'] = $pdf_file;
        if($audio_file)
            $data['audio_file'] = $audio_file;

            return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_CREATED);
    }

    public function getLink(book $book)
    {
        $link = url("/api/books/{$book->id}");
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$link],Response::HTTP_OK);
    }

    public function PdfLink(book $book)
    {
        $link = null;
        if($book->PDF){
            $link = "$book->PDF";
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$link],Response::HTTP_OK);
    }

    static public function newPrice(book $book)
    {
        $discount = discount::where('book_id', $book->id)
        ->where('start_date', '<=', date('Y-n-j'))
        ->where('end_date', '>=', date('Y-n-j'))
        ->first();
        $new_price = null;
        if($discount)
        {
            $new_price = $book->price - ($book->price * ($discount->ratio / 100));
        }

        return $new_price;
    }

    static public function showRate(Book $book)
    {
        if(rate::where('book_id',$book->id)->count('stars_number') == 0)
            return ['rate'=>0, "number of stars"=>0];

        $rate = rate::where('book_id',$book->id)->sum('stars_number') /
                (rate::where('book_id',$book->id)->count('stars_number') * 5) *100 ;
        $number = $rate * 5 /100;
        return ["rate"=> $rate,  "number of stars"=>$number];

    }

    public function show(book $book)
    {
        $authors = [];
        $categories = [];
        $data['book'] = $book;
        if($book->amateur){
            $data['rate'] = BookController::showRate($book);

            $amateur = amateure_writer::where('name',$book->name)->where('description',$book->description)->first();
            $author = User::where('id',$amateur->user_id)->select('id' ,author::raw("CONCAT(first_name,' ',last_name) AS name"), 'photo')->first();
            $data['author'] = $author;

            $category_id = category_book::where('book_id', $book->id)->get();
            foreach($category_id as $id) {
                array_push($categories, category::where('id', $id->category_id)->select('name')->first());
            }
            $data['categories'] = $categories;

        }
        else{
            $new_price = BookController::newPrice($book);
            if($new_price)
            {
                $data['new_price'] = $new_price;
            }
    
            $data['rate'] = BookController::showRate($book);
    
            $authors_id = book_author::where('book_id',$book->id)->get();
            foreach($authors_id as $id) {
                array_push($authors,author::where('id',$id->author_id)->select('id' ,author::raw("CONCAT(first_name,' ',last_name) AS name"), 'photo')->first());
                }
            $data['authors'] = $authors;
    
            $category_id = category_book::where('book_id', $book->id)->get();
            foreach($category_id as $id) {
                array_push($categories, category::where('id', $id->category_id)->select('name')->first());
            }
            $data['categories'] = $categories;
        }
        
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        if($res = $request->name)
        {
            $books = Book::where('name','LIKE','%'.$res.'%')
            ->select('id', 'name', 'cover', 'description')
            ->get();

            return response()->json(['status'=> 200 ,'message'=>'successful', 'data'=>$books],Response::HTTP_OK);
        }

        return response()->json(['status'=> 204 ,'message'=>'no input', 'data'=>null],Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request, book $book)
    {
        $Validator = Validator::make($request->all(),[
            'name'=>['nullable','string' , 'min:1'],
            'description'=>['nullable','string'],
            'page_number'=>['nullable' , 'numeric', 'min:0'],
            'publishing_house'=>['nullable','string'],
            'copies_number'=>['nullable', 'numeric','min:0'],
            'price'=>['nullable', 'numeric','min:0'],
            'classification'=>['nullable' , 'string'],
            'publishing_year'=>['nullable' , 'date'],
            'state'=>['nullable','boolean']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('name')){
            $name=$request->input('name');
            $book->update([
               'name'=> $name,
            ]);
        }

        if($request->has('description')){
            $description=$request->input('description');
            $book->update([
               'description'=> $description,
            ]);
        }

        if($request->has('page_number')){
            $page_number=$request->input('page_number');
            $book->update([
               'page_number'=> $page_number,
            ]);
        }

        if($request->has('publishing_house')){
            $publishing_house=$request->input('publishing_house');
            $book->update([
               'publishing_house'=> $publishing_house,
            ]);
        }

        if($request->has('publishing_year')){
            $publishing_year=$request->input('publishing_year');
            $book->update([
               'publishing_year'=> $publishing_year,
            ]);
        }

        if($request->has('copies_number')){
            $copies_number = $request->input('copies_number');
            $book->update([
               'copies_number'=> $copies_number,
            ]);

        }

        if($request->has('state')){
            $state=$request->input('state');
            $book->update([
               'state'=> $state,
            ]);
        }

        if($request->has('price')){
            $price=$request->input('price');
            $book->update([
               'price'=> $price,
            ]);
        }

        if($request->has('classification')){
            $classification=$request->input('classification');
            $book->update([
               'classification'=> $classification,
            ]);
        }


        $pdf_file = null;
        if($request->PDF)
        {
            $pdf_validator = Validator::make($request->all(), [
                'PDF'=>'mimes:pdf'
            ]);
            if($pdf_validator->fails()){
                return response()->json(['status' => 400 ,'message' => $pdf_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }
            $file_extension2 = $request->PDF->getClientOriginalExtension();
            $file_name2 = time().'.'.$file_extension2;
            $path2 = 'files/pdf';
            $request->PDF->move($path2, $file_name2);
            $pdf_file = "public/files/pdf/$file_name2";

            $book->update([
                'PDF'=> $file_name2,
             ]);
        }

        $audio_file = null;
        if($request->audio_book)
        {
            $audio_validator = Validator::make($request->all(), [
                'audio_book'=>'mimes:mp3,wav,ogg'
            ]);
            if($audio_validator->fails()){
                return response()->json(['status' => 400 ,'message' => $audio_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }

            $file_extension3 = $request->audio_book->getClientOriginalExtension();
            $file_name3 = time().'.'.$file_extension3;
            $path3 = 'files/audio';
            $request->audio_book->move($path3, $file_name3);
            $audio_file = "public/files/audio/$file_name3";

            $book->update([
                'audio_book'=> $file_name3,
             ]);
        }

        $photo_file=null;
        if($request->cover){
            $image_validator = Validator::make($request->all(),[
                'cover'=>'mimes:png,jpg,jpeg,bmp,gif'
            ]);
            if($image_validator->fails()){
                return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages(), 'data'=>null], Response::HTTP_BAD_REQUEST);
            }
            $file_extension=$request->cover->getClientOriginalExtension();
            $file_name=time().'.'.$file_extension;
            $path='photos/books';
            $request->cover->move($path,$file_name);

            $book->update([
                'cover'=>$file_name,
            ]);
            $photo_file="public/photos/books/$file_name";
        }

        $data['book'] = $book;
        if($photo_file)
        {
            $data['photo_file'] = $photo_file;
        }
        if($audio_file)
        {
            $data['audio_file'] = $audio_file;
        }
        if($pdf_file)
        {
            $data['pdf_file'] = $pdf_file;
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function destroy(book $book)
    {
        $book->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$book],Response::HTTP_NO_CONTENT);
    }
}
