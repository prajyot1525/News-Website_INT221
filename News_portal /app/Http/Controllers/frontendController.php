<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http; 
// use Illuminate\Support\Facades\Validator; 
use Illuminate\Http\Request;
use App\Models\login;

class frontendController extends Controller
{

    
  function getData(Request $req){
    $req->validate([
        'email'=>'required',
        'password'=>'required | min:6',
    ]);
     $login=new login;
     $login->email=$req->email;
     $login->password=$req->password;
     $login->save();

     $req->session()->put('datakey',$login['email']);
     
     return redirect('news');
    
}

    function topHeadlines(){
        $topHeadlineIN ="https://newsapi.org/v2/top-headlines?country=in&category=general&apiKey=a2d0270761d144b88aedbe39b562fc20";
        $data= Http::get($topHeadlineIN)->json();
        return view("frontend.master",["collectionOfNews"=>$data["articles"]]);  
      } 
    
      // CUstom Searching
      function countryCode(Request $req){
        $countryName=$req->input('countryName');
        $Category=$req->input('Category');
        
        if ($Category==NULL) {
          $Category="general";
        }
    
        $topHeadlineIN ="https://newsapi.org/v2/top-headlines?country=$countryName&category=$Category&apiKey=a2d0270761d144b88aedbe39b562fc20";
        $data= Http::get($topHeadlineIN)->json();
    
        return view("frontend.master",["collectionOfNews"=>$data["articles"]]); 
      }
    
      
      // Searching
      function search(Request $req,$generalTopic=NULL ){
    
        if ($generalTopic==NULL) {
          $searchTxt=$req->input('searchInput');
          $searchApi= "https://newsapi.org/v2/everything?q=$searchTxt&sortBy=publishedAt&apiKey=a2d0270761d144b88aedbe39b562fc20";
        }
    
        else{
          $searchApi= "https://newsapi.org/v2/everything?q=$generalTopic&sortBy=publishedAt&apiKey=a2d0270761d144b88aedbe39b562fc20";
        }
        $data= Http::get($searchApi)->json();
    
        return view("frontend.master",["collectionOfNews"=>$data["articles"]]); 
      }
    
    
     
}

// function register(Request $request)
//     {
//       if($request->session()->has('user_login')!=null){
//        return redirect('news'); 
//       }
//       $result=[];
//       return view('backend.signup',$result);
//     }
    
//     function signup_process(Request $request){
//       $valid=Validator::make($request->all(),[
//         "username"=>'required',
//         "email"=>'required|email|unique:datas,email',
//         "password"=>'required',
//       ]);
      
//       if(!$valid->passes()){
//         return response()->json(['status'=>'error',
//         'error'=>$valid->errors()->toArray()]);
//       }
      
//       else{
//         $arr=[
//           "username"=>$request->username,
//           "email"=>$request->email,
//           "password"=>$request->password,
//           "status"=>1
//         ];
//         $query=DB::table('datas')->insert($arr);
//         if($query){
//           return response()->json(['status'=>'success',
//         'msg'=>"User has been registered successfuly"]);
//         }
//       }
//     }

//     function login_process(Request $request){
//       $result=DB::table('datas')
//       ->where(['email'=>$request->emailin])
//       ->get();
//       if(isset($result[0])){
//         $request->session()->put('user_login',true);
        
//         $db_pwd=$result->password;
//         if($db_pwd==$request->passwordin)
//         {
          
//           $status="success";
//         }
//         else{
//           $status="error";
//         $msg="Please enter valid password";
//         }   
//       }
//       else{
//         $status="error";
//         $msg="Please enter valid email id";
//       }
      
//       return response()->json(['status'=>$status,
//         'msg'=>$msg]);
        
//     }