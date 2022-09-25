<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\AddressController;
use App\HTTP\Controllers\CategoryController;
use App\HTTP\Controllers\RoleController;
use App\HTTP\Controllers\AuthorController;
use App\HTTP\Controllers\FavoriteController;
use App\HTTP\Controllers\CategoryUserController;
use App\HTTP\Controllers\PlaylistController;
use App\HTTP\Controllers\PlaylistBookController;
use App\HTTP\Controllers\BookController;
use App\HTTP\Controllers\BookAuthorController;
use App\HTTP\Controllers\CategoryBookController;
use App\HTTP\Controllers\ReviewController;
use App\HTTP\Controllers\LikeController;
use App\HTTP\Controllers\DiscountController;
use App\HTTP\Controllers\ReservedController;
use App\HTTP\Controllers\RateController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\WallController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AmateureWriterController;
use App\Http\Controllers\AmateureAdminController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\DonateAdminController;
use App\Http\Controllers\BookDonateController;
use App\Http\Controllers\DonateCartController;

// Route::middleware(['auth:sanctum','verified'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/test-online',function(){
    return 'on line';
});

Route::get('a',[UserController::class,'alluser'])->middleware('auth:api');

Route::post('sign-up', [UserController::class , 'store']);
Route::post('login', [UserController::class , 'login']);

Route::post('email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware('auth:api');
Route::get('verify/email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:api');

Route::prefix('address')->group(function(){
    Route::get('/',[AddressController::class,'index']);
    Route::get('country',[AddressController::class,'country']);
    Route::get('state',[AddressController::class,'state']);
    Route::get('city',[AddressController::class,'city']);
    Route::get('street',[AddressController::class,'street']);
    Route::get('country/shipper',[AddressController::class,'countryShipper']);
    Route::get('state/shipper',[AddressController::class,'stateShipper']);
    Route::get('city/shipper',[AddressController::class,'cityShipper']);
    Route::get('city/street',[AddressController::class,'streetShipper']);
    Route::get('/{address}',[AddressController::class,'show']);
});

Route::middleware(['auth:api' , 'verified' , 'ActiveAccount'])->group(function(){
//Route::middleware(['auth:api'])->group(function(){
    Route::prefix('roles')->group(function(){
        Route::get('/',[RoleController::class,'index']);
    });

    Route::prefix('categories')->group(function(){
        Route::get('/',[CategoryController::class,'index']);
        Route::get('/{category}',[CategoryController::class,'show']);
    });

    Route::prefix('authors')->group(function(){
        Route::get('/',[AuthorController::class,'index']);
        Route::get('/{author}',[AuthorController::class,'show']);
        Route::post('/search',[AuthorController::class,'search']);
    });

    Route::prefix('books')->group(function(){
        Route::get('/',[BookController::class,'index']);
        Route::get('/url/{book}',[BookController::class,'getLink']);
        Route::get('/pdf/{book}',[BookController::class,'PdfLink']);
        Route::get('/{book}',[BookController::class,'show']);
        Route::post('/search',[BookController::class,'search']);
    });

    Route::prefix('users')->group(function(){
        Route::post('/logout',[UserController::class,'logout']);
        Route::middleware(['AccessSuperAdminControl'])->group(function(){
            Route::post('/addSuperAdmin/{user}',[UserController::class,'addSuperAdmin']);
            Route::post('/addAdmin/{user}',[UserController::class,'addAdmin']);
            Route::post('/addShipper/{user}',[UserController::class,'addShipper']);
            Route::post('/addUser/{user}',[UserController::class,'addUser']);
        });
        Route::get('/url/{user}',[UserController::class,'getLink']);
        Route::get('/{user}',[UserController::class , 'show']);
        Route::post('edit/{user}', [UserController::class , 'update'])->middleware(['AccessUserControl']);
        Route::delete('/{user}', [UserController::class , 'destroy'])->middleware(['AccessControl']);
    });

    Route::prefix('category_users')->group(function(){
        Route::post('/',[CategoryUserController::class,'store']);
        Route::middleware(['AccessUserControl'])->group(function(){
            Route::get('/{user}',[CategoryUserController::class,'index']);
            Route::put('/{category_user}',[CategoryUserController::class,'update']);
            Route::delete('/{category_user}',[CategoryUserController::class,'destroy']);
        });
    });

    Route::prefix('home')->group(function(){
        Route::get('best/seller',[HomeController::class,'seller']);
        Route::get('/new/book',[HomeController::class,'newBook']);
        Route::get('/hight/rate',[HomeController::class,'highRate']);
        Route::get('/amateure',[HomeController::class,'amateure_writer_pdf']);
        Route::get('/categories/user',[HomeController::class,'categories']);
        Route::get('/advertisement',[HomeController::class,'advertisement']);
    });

    Route::prefix('favorite')->group(function(){
        Route::get('/{user}',[FavoriteController::class,'index']);
        Route::post('/{author}',[FavoriteController::class,'store']);
        Route::delete('/{favorite}',[FavoriteController::class,'destroy']);
    });

    Route::prefix('playlist')->group(function(){
        Route::get('for/{user}',[PlaylistController::class,'index']);
        Route::post('/',[PlaylistController::class,'store']);
        Route::get('/{playlist}',[PlaylistController::class,'show']);
        Route::middleware(['AccessUserControl'])->group(function(){
            Route::put('/{playlist}',[PlaylistController::class,'update']);
            Route::delete('/{playlist}',[PlaylistController::class,'destroy']);
        });

    });

    Route::prefix('playlist_book')->group(function(){
        Route::post('/{playlist}/{book}',[PlaylistBookController::class,'store']);
        Route::delete('/{playlist_book}',[PlaylistBookController::class,'destroy']);
    });

    Route::prefix('reviews')->group(function(){
        Route::get('/{book}',[ReviewController::class,'index']);
        Route::get('/show/{review}',[ReviewController::class,'show']);
        Route::get('showReplies/{review}',[ReviewController::class,'showReplies']);
        Route::get('switch/{review}',[ReviewController::class,'switch']);
        Route::post('/',[ReviewController::class,'store']);
        Route::put('/{review}',[ReviewController::class,'update'])->middleware(['AccessUserControl']);
        Route::delete('/{review}',[ReviewController::class,'destroy'])->middleware(['AccessControl']);
    });

    Route::prefix('likes')->group(function(){
        Route::post('/',[LikeController::class,'store']);
    });

    Route::prefix('walls')->group(function(){
        Route::get('/{wall}',[WallController::class,'show']);
        Route::delete('/{wall}',[WallController::class,'destroy'])->middleware(['AccessUserControl']);
    });

    Route::prefix('conversations')->group(function(){
        Route::get('/{conversation}',[ConversationController::class,'show']);
        Route::post('/',[ConversationController::class,'store']);
        Route::put('/{conversation}',[ConversationController::class,'update'])->middleware(['AccessUserControl']);
        Route::delete('/{conversation}',[ConversationController::class,'destroy'])->middleware(['AccessControl']);
    });

    Route::prefix('rates')->group(function(){
        Route::post('/',[RateController::class,'store']);
        Route::middleware(['AccessUserControl'])->group(function(){
            Route::put('/{rate}',[RateController::class,'update']);
            Route::delete('/{rate}',[RateController::class,'destroy']);
        });
    });

    Route::prefix('discounts')->group(function(){
        Route::get('/',[DiscountController::class,'index']);
    });

    Route::prefix('reserveds')->group(function(){
        Route::post('/',[ReservedController::class,'store']);
    });

    Route::prefix('amateure_writers')->group(function(){
        Route::get('/check',[AmateureWriterController::class,'check']);
        Route::post('/',[AmateureWriterController::class,'store']);
        Route::get('/show/{amateure_writer}',[AmateureWriterController::class,'show'])->middleware(['AccessControl']);
    });

    Route::prefix('donates')->group(function(){
        Route::post('/',[DonateController::class,'store']);
        Route::get('/show/{donate}',[DonateController::class,'show'])->middleware(['AccessControl']);
        Route::delete('/{donate}',[DonateController::class,'destroy'])->middleware(['AccessControl']);
    });

    Route::prefix('book_donate')->group(function(){
        Route::get('/',[BookDonateController::class,'index']);
        Route::post('/',[BookDonateController::class,'store']);
        Route::delete('/{book_donate}',[BookDonateController::class,'destroy'])->middleware(['AccessControl']);
    });

    Route::prefix('donate_carts')->group(function(){
        Route::post('/',[DonateCartController::class,'store']);
    });

    Route::prefix('carts')->group(function(){
        Route::post('/',[CartController::class,'store']);
    });

    Route::prefix('items')->group(function(){
        Route::post('/',[ItemController::class,'addToCart']);
    });

    Route::middleware(['AccessUserControl'])->group(function(){
        Route::prefix('reserveds')->group(function(){
            Route::get('/{user}',[ReservedController::class,'index']);
            Route::put('/{reserved}',[ReservedController::class,'update']);
            Route::delete('/{reserved}',[ReservedController::class,'destroy']);
        });

        Route::prefix('amateure_writers')->group(function(){
            Route::get('/{user}',[AmateureWriterController::class,'index']);
            Route::post('/{amateure_writer}',[AmateureWriterController::class,'update']);
            Route::delete('/{amateure_writer}',[AmateureWriterController::class,'destroy']);
        });

        Route::prefix('donates')->group(function(){
            Route::get('/{user}',[DonateController::class,'index']);
            Route::put('/{donate}',[DonateController::class,'update']);
        });

        Route::prefix('book_donate')->group(function(){
            Route::post('/{book_donate}',[BookDonateController::class,'update']);
        });

        Route::prefix('donate_carts')->group(function(){
            Route::delete('/{donate_cart}',[DonateCartController::class,'destroy']);
        });

        Route::prefix('carts')->group(function(){
            Route::get('/{cart}',[CartController::class,'show']);
            Route::delete('/{cart}',[CartController::class,'destroy']);
        });

        Route::prefix('items')->group(function(){
            Route::delete('/{item}',[ItemController::class,'deleteFromCart']);
            Route::put('/{item}',[ItemController::class,'update']);
        });

        Route::prefix('bills')->group(function(){
            Route::get('/{user}',[BillController::class,'index']);
            Route::get('/not/{user}',[BillController::class,'indexNot']);
            Route::post('/{cart}',[BillController::class,'store']);
            Route::put('/{bill}',[BillController::class,'update']);
            Route::delete('/{bill}',[BillController::class,'destroy']);
        });
    });

    Route::prefix('bills')->group(function(){
        Route::get('/show/{bill}',[BillController::class,'show'])->middleware('AccessControl');
    });

    Route::middleware(['AccessAdminControl'])->group(function(){

        // Route::prefix('roles')->group(function(){
        //     Route::post('/',[RoleController::class,'store']);
        //     Route::put('/{role}',[RoleController::class,'update']);
        //     Route::delete('/{role}',[RoleController::class,'destroy']);
        // });

        // Route::prefix('address')->group(function(){
        //     Route::post('/',[AddressController::class,'store']);
        //     Route::delete('/{address}',[AddressController::class,'destroy']);
        // });

        Route::prefix('categories')->group(function(){
            Route::post('/',[CategoryController::class,'store']);
            Route::post('/{category}',[CategoryController::class,'update']);
            Route::delete('/{category}',[CategoryController::class,'destroy']);
        });

        Route::prefix('authors')->group(function(){
            Route::post('/',[AuthorController::class,'store']);
            Route::post('/{author}',[AuthorController::class,'update']);
            Route::delete('/{author}',[AuthorController::class,'destroy']);
        });

        Route::prefix('books')->group(function(){
            Route::post('/',[BookController::class,'store']);
            Route::post('/{book}',[BookController::class,'update']);
            Route::delete('/{book}',[BookController::class,'destroy']);
        });

        Route::prefix('category_book')->group(function(){
            Route::post('/',[CategoryBookController::class,'store']);
            Route::put('/{category_book}',[CategoryBookController::class,'update']);
            Route::delete('/{category_book}',[CategoryBookController::class,'destroy']);
        });

        Route::prefix('book_author')->group(function(){
            Route::post('/',[BookAuthorController::class,'store']);
            Route::put('/{book_author}',[BookAuthorController::class,'update']);
            Route::delete('/{book_author}',[BookAuthorController::class,'destroy']);
        });

        Route::prefix('discounts')->group(function(){
            Route::post('/',[DiscountController::class,'store']);
            Route::post('/{discount}',[DiscountController::class,'update']);
            Route::delete('/{discount}',[DiscountController::class,'destroy']);
        });

        Route::prefix('bills')->group(function(){
            Route::get('/all/orders',[BillController::class,'orders']);
            Route::get('/all/orders/not',[BillController::class,'ordersNot']);
        });

        Route::prefix('amateure_admins')->group(function(){
            Route::get('/',[AmateureAdminController::class,'index']);//accepted
            Route::get('/not',[AmateureAdminController::class,'indexNot']);
            Route::get('/accept/{amateure_writer}',[AmateureAdminController::class,'accept']);
            Route::get('/reject/{amateure_writer}',[AmateureAdminController::class,'reject']);
        });

        Route::prefix('book_donate')->group(function(){
            Route::put('/update/{book_donate}',[BookDonateController::class,'update_admin']);
        });

        Route::prefix('donate_admin')->group(function(){
            Route::get('/accept/{donate}',[DonateAdminController::class,'accept']);
            Route::get('/reject/{donate}',[DonateAdminController::class,'reject']);
            Route::get('/',[DonateAdminController::class,'index']);
            Route::get('/not',[DonateAdminController::class,'indexNot']);
        });

    });

});
