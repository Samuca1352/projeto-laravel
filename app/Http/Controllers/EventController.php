<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;

use App\Models\User;

class EventController extends Controller
{
    
    public function index(){
        $search = request("search");
        $events = $search? Event::where([["title","like","%$search%"]])->get() : Event::all();

        return view('welcome',["events"=>$events, "search"=>$search]);
    }
    
    public function create(){
        return view('events.create');   
    }
    
public function store(Request $request)
{
    // Validação
    $request->validate([
        'title' => 'required',
        'date' => 'required|date',
        'city' => 'required',
        'private' => 'required|boolean',
        'description' => 'required',
        'items' => 'required|array',
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ], [
        'image.required' => 'Por favor, envie uma imagem para o evento.',
        'image.image' => 'O arquivo enviado precisa ser uma imagem.',
        'image.mimes' => 'A imagem deve estar no formato JPG, JPEG ou PNG.',
        'image.max' => 'A imagem não pode ter mais de 2MB.'
    ]);

    // Criação do evento
    $event = new Event;

    $event->title = $request->title;
    $event->date = $request->date;
    $event->city = $request->city;
    $event->private = $request->private;
    $event->description = $request->description;
    $event->items = $request->items;

    // Upload da imagem
    $requestImage = $request->image;
    $extension = $requestImage->extension();
    $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
    $requestImage->move(public_path("img/events"), $imageName);
    $event->image = $imageName;

    // Associar usuário autenticado
    $user = auth()->user();
    $event->user_id = $user->id;

    $event->save();

    return redirect("/")->with("msg", "Evento criado com sucesso!");
}


    public function show($id){
        $event = Event::findOrFail($id);
        
        $user = auth()->user();

        $hasUserJoined = false;

        if($user){
            $userEvents = $user->eventsAsParticipant->toArray();
            foreach($userEvents as $userEvent){
                if($userEvent["id"] == $id){
                    $hasUserJoined = true;
                }
            }
        }

        $eventOwner = User::where("id", $event->user_id)->first()->toArray();


        return view("events.show",["event" => $event, "eventOwner" => $eventOwner, "hasUserJoined" => $hasUserJoined]);
    }

    public function dashboard(){
        $user = auth()->user();
        $events = $user->events;
        $eventsAsParticipants = $user->eventsAsParticipant;
        return view("events.dashboard", ["events" => $events,'eventsasparticipant' => $eventsAsParticipants]);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);

    // Remove todos os participantes relacionados
        $event->users()->detach();

    // Agora pode deletar com segurança
        $event->delete();

        return redirect("/dashboard")->with("msg", "Evento excluído com sucesso!");
}


    public function edit($id){
        $user = auth()->user();
        $event = Event::findOrFail($id);

        if($user->id != $event->user->id){
            return redirect('/dashboard');
        }

        return view("events.edit",["event"=> $event]);
    }

    public function update(Request $request){
        $data = $request->all();
        $requestImage = $request->image;
        $extension = $requestImage->extension();
        $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $requestImage->move(public_path("img/events"),$imageName);
        $data["image"] = $imageName;
        Event::findOrFail($request->id)->update($data);
        return redirect("/dashboard")->with("msg","Evento editado com sucesso!");
    }

    public function joinEvent($id){
        $user = auth()->user();
        $user->eventsAsParticipant()->attach($id);
        $event = Event::findOrFail($id);
        return redirect('/dashboard')->with('msg','Sua presença está confirmada no evento ' . $event->title);
    }

    public function leaveEvent($id){
        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);
        $event = Event::findOrFail($id);
        return redirect('/dashboard')->with('msg','Você saiu com sucesso do evento: ' . $event->title);

    }

}
