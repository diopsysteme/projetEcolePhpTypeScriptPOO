<?php
namespace Controller;

use App\App;
use Core\Authorize;
use Core\Controller;
use Entity\ClientEntity;
use Core\Validator;
use Core\File;
use Core\Model;
use Core\Validator2;
use Model\SessionModel;
use Model\UserModel;
use Model\CourseModel;
use Model\CourseclasseModel;


use Core\Session;

class SessionController extends Controller
{
    private $courseModel;
    private $sessionModel;
    private $userModel;
    private $dettearticleModel;
    private $articleModel;
    private $etudiantModel;
    private $presenceModel;
    private $justifModel;
    public function __construct(Session $session, Validator2 $validator, File $file,Authorize $authorize)
    {
        parent::__construct($session, $validator, $file,$authorize);
        $this->courseModel = App::getInstance()->getModel("Course");
        $this->sessionModel = App::getInstance()->getModel("Session");
        $this->userModel = App::getInstance()->getModel("User");
        $this->etudiantModel = App::getInstance()->getModel("Etudiant");
        $this->presenceModel = App::getInstance()->getModel("Presence");
        $this->justifModel = App::getInstance()->getModel("Justification");
    }
    public function listSession()
{
    $dd = $this->userModel->belongsTomany(SessionModel::class, "idproffesseur", 2, CourseModel::class, "idcours");
    $events = [];
    foreach ($dd as $session) {
        $events[] = [
            'id' => $session->id,
            'title' => $session->libelle,
            'start' => $session->date . 'T' . $session->heureDemarrage,
            'end' => $session->date . 'T' . $session->heureFin,
            'state'=>$session->statut
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($events);
    exit; // Make sure to exit after sending JSON
}
public function listSession2()
{

   $user= $this->session::get('user');
   if (!$user){
    $this->renderView('login/login', [],'neant');
    return;
   }
    // Retrieve sessions using the belongsToMany relationship
    $coursProf=$this->userModel
    ->belongsTo(CourseModel::class,"idproffesseur", $user->iduser);
    $sessions=[];
    foreach ($coursProf as $course) {
        // Charger toutes les sessions liées à un cours
        $courseSessions = $this->courseModel->hasMany(SessionModel::class, "idcours", $course->id);
        foreach ($courseSessions as $session) {
            // Ajouter le libellé du cours à chaque session
            $session->libelle = $course->libelle;
            // Afficher des informations pour le débogage
            $sessions[] = $session;
        }
        
        // Ajouter les sessions au tableau des sessions
    }
        // Define a mapping array for the status colors
    $statusColors = [
        'done' => '#28a745', // Green for done
        'programmed' => '#ffc107', // Yellow for programmed
        'canceled' => '#dc3545', // Red for canceled
    ];
    // Prepare the events array
    $events = [];
    foreach ($sessions as $session) {
        $events[] = [
            'id' => $session->id,
            'title' => $session->libelle,
            'start' => $session->date . 'T' . $session->heuredebut,
            'end' => $session->date . 'T' . $session->heurefin,
            'color' => $statusColors[$session->statut] ?? '#000000', // Default color if status not  found
            'state' => $session->statut,
            'start1'=>$session->heureDemarrage,
            'end1'=>$session->heureFin,
        ];
    }

    // Pass the events array to the view, encoded as JSON
    $this->renderView('session/sessionList', ['events' => json_encode($events),"clients"=>$user]);
}
public function cancel()
{
    // Set the response header to JSON
    header('Content-Type: application/json');

    try {
        // Retrieve the input data from the POST request
        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = $input['id'] ?? null;

        // Debug output
        error_log("Session ID: " . $sessionId);

        if (!$sessionId) {
            throw new \Exception('Invalid session ID');
        }

        // Load the session model and find the session by ID
        $session = $this->sessionModel->searchByAttribute("id", $sessionId);
        if (!$session) {
            throw new \Exception('Session not found');
        }

        // Get the first session object
        $session = $session[0];
        $data = [
            "id" => $session->id,
            "statut" => "canceled"
        ];
        // $data2 = [
        //     "id" => 3,
        //     "password" => password_hash("passer", PASSWORD_DEFAULT),
        // ];
       
        $this->sessionModel->save($data);
        // $this->userModel->save($data2);
        // Return a JSON response indicating success
        $response = ['success' => true];
        echo json_encode($response);
        error_log("Response: " . json_encode($response));
    } catch (\Exception $e) {
        // Log the error
        error_log($e->getMessage());

        // Return a JSON response indicating an error
        $response = ['success' => false, 'message' => $e->getMessage()];
        echo json_encode($response);
        error_log("Error response: " . json_encode($response));
    }
}


public function showCourse(){
    
    $user= $this->session::get('user');
    if (!$user){
        $this->renderView('login/login', [],'neant');
        return;
       }
       $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
       $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) :2;
       $offset = ($page - 1) * $pageSize;
       (int)$prev = ($page > 1) ? $page - 1 : 1;
       $call2 = $this->userModel->belongsTomany(SessionModel::class, "idproffesseur", $user->id, CourseModel::class, "idcours");
       $possible=ceil(count($call2)/$pageSize);
       $suiv = ($page < $possible) ? $page + 1 : $possible;
    $sessions = $this->userModel->belongsTomany(SessionModel::class, "idproffesseur", $user->id, CourseModel::class, "idcours",0,2);
    $this->renderView('session/sessionList2', ['sessions' =>$sessions,"prev"=>$prev,"suiv"=> $suiv,"clients"=>$user]);
}
public function showCourseP(){
    $user= $this->session::get('user');
    if (!$user){
    $this->renderView('login/login', [],'neant');
    return;
   }
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) :2;
    $offset = ($page - 1) * $pageSize;
    (int)$prev = ($page > 1) ? $page - 1 : 1;
    $call2 = $this->userModel->belongsTomany(SessionModel::class, "idproffesseur", $user->id, CourseModel::class, "idcours");
    $possible=ceil(count($call2)/$pageSize);
    $suiv = ($page < $possible) ? $page + 1 : $possible;
    $sessions = $this->userModel->belongsTomany(SessionModel::class, "idproffesseur", $user->id, CourseModel::class, "idcours",$offset, $pageSize);
    $this->renderView('session/sessionList2', ['sessions' =>$sessions,"prev"=>$prev,"suiv"=> $suiv,"clients"=>$user]);
}


// showCourseE
// showSessionE
// showAbsence
    public function showCourseE(){
        $user= $this->session::get('user');
        if (!$user){
            $this->renderView('login/login', [],'neant');
            return;
           }
           $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
           $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) :2;
           $offset = ($page - 1) * $pageSize;
           (int)$prev = ($page > 1) ? $page - 1 : 1;
          $etudiant= $this->etudiantModel->searchByAttribute("id", $user->iduser)[0];

          $call2 = $this->userModel->belongsTomany(CourseModel::class, "idcours", $etudiant->idclasse, CourseclasseModel::class, "idclasse");
           $possible=ceil(count($call2)/$pageSize);
           $suiv = ($page < $possible) ? $page + 1 : $possible;
        $sessions = $this->session->get("user");

         $this->renderView('session/sessionList2', ['courses' =>$call2,"prev"=>$prev,"suiv"=> $suiv,"clients"=>$user]);
    }
    public function showSessionE(){
        {
            $user= $this->session::get('user');
            if (!$user){
             $this->renderView('login/login', [],'neant');
             return;
            }
            echo date("H:i:s") ."". $user->iduser ."";
            $etudiant= $this->etudiantModel->searchByAttribute("id", $user->iduser)[0];
            
            $call2 = $this->userModel->belongsTomany(CourseModel::class, "idcours", $etudiant->idclasse, CourseclasseModel::class, "idclasse");
             $coursProf=$this->userModel
             ->belongsTo(CourseModel::class,"idproffesseur", $user->iduser);
             $sessions=[];
             foreach ($call2 as $course) {
                 $courseSessions = $this->courseModel->hasMany(SessionModel::class, "idcours", $course->id);
                 foreach ($courseSessions as $session) {
                     $session->libelle = $course->libelle;
                     $sessions[] = $session;
                 }
             }
             $statusColors = [
                 'done' => '#28a745',
                 'programmed' => '#ffc107',
                 'canceled' => '#dc3545',
             ];
             $events = [];
             foreach ($sessions as $session) {
                 $events[] = [
                     'id' => $session->id,
                     'title' => $session->libelle,
                     'start' => $session->date . 'T' . $session->heuredebut,
                     'end' => $session->date .'T' . $session->heurefin,
                     'color' => $statusColors[$session->statut] ?? '#000000',
                     'state' => $session->statut,
                     'start1'=>$session->heureDemarrage,
                     'end1'=>$session->heureFin,
                 ];
             }
         
             $this->renderView('session/sessionList', ['events' => json_encode($events),"user"=>$user]);
         }
    }

    public function savePresence(){
        header('Content-Type: application/json');
    
        // Obtenez les données d'entrée JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = $input['id'] ?? null;
        $etudiantId = $this->session::get('user')->iduser ?? null;
    
        // Vérifiez si les ID de session et d'étudiant sont présents
        if (!$sessionId || !$etudiantId) {
            error_log("Session ID or Student ID missing");
            echo json_encode([
                'success' => false,
                'message' => 'Session ID and Student ID are required.'
            ]);
            return;
        }
    
        // Vérifiez si l'étudiant a déjà marqué sa présence pour cette session et cette date
        $this->presenceModel->setClauses(["date = :date"]);
        $this->presenceModel->setFilters([":date" => date("Y-m-d")]);
        $existingPresence = $this->presenceModel->belongsTo(\Model\PresenceModel::class, "idetudiant", $etudiantId);
    
        if ($existingPresence) {
            error_log("Presence already marked");
            echo json_encode([
                'success' => false,
                'message' => 'Presence already marked.'
            ]);
            return;
        }
    
        // Préparez les données de présence
        $data = [
            "idsession" => $sessionId,
            "idetudiant" => $etudiantId,
            "date" => date("Y-m-d"),
            "heure" => date("H:i:s")
        ];
    
        // Enregistrez la présence
        try {
            $this->presenceModel->save($data);
            echo json_encode([
                'success' => true,
                'message' => 'Presence marked successfully.'
            ]);
        } catch (\Exception $e) {
            error_log("Error saving presence: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while marking presence.'
            ]);
        }
    }

    public function showAbsence($error=[]){
        $user= $this->session::get('user');
        if (!$user){
            $this->renderView('login/login', [],'neant');
            return;
           }
           $etudiant= $this->etudiantModel->searchByAttribute("id", $user->iduser)[0];
            
           $call2 = $this->userModel->belongsTomany(CourseModel::class, "idcours", $etudiant->idclasse, CourseclasseModel::class, "idclasse");
            $coursProf=$this->userModel
            ->belongsTo(CourseModel::class,"idproffesseur", $user->iduser);
            $sessions=[];
            foreach ($call2 as $course) {
                $this->courseModel->setClauses(["date<:date"]);
                $this->courseModel->setFilters(["date"=>date("Y-m-d")]);
                $courseSessions = $this->courseModel->hasMany(SessionModel::class, "idcours", $course->id);
                foreach ($courseSessions as $session) {
                    $session->libelle = $course->libelle;
                    $sessions[] = $session;
                }
            }
        $this->presenceModel->setClauses(["idetudiant=:idetudiant"]);
        $this->presenceModel->setFilters(["idetudiant"=>$user->iduser]);
        $presences=$this->presenceModel-> all();
        // var_dump($presences);,×;
        $absences=[];
        foreach ($sessions as $session){
            $present=false;
            foreach ($presences as $presence){
                
                if($presence->idsession == $session->id && $presence->date==$session->date ){
                    $present=true;
                }
            }
            if(!$present){
                $absences[] = $session;
            }
        }
        // var_dump($absences);
        $this->renderView('absence/absence', ['absences' => $absences,"user"=>$user,"error"=>$error]);

    }
    public function saveJustif(){
        $user= $this->session::get('user');
        if (!$user){
            $this->renderView('login/login', [],'neant');
            return;
           }
        var_dump($_POST);
        var_dump($_FILES["justif"]);
        if(!empty($_FILES["justif"]["name"])&& !empty($_POST["motif"]&&!empty($_POST["session"]))){

            $name="piecejointe".time();
            $this->file->upload($_FILES["justif"],$name);
            $justif=[
                "idetudiant"=> $user->iduser,
                "idsession"=> $_POST["session"],
                "motif"=> $_POST["motif"],
                "piece"=>$name
            ];
            $this->justifModel->save($justif);
            $this->redirect("/etudiant/absence");
        }
        if(empty($_POST["motif"])){
            $error["motif"] = "motif not found";
        }
        if(empty($_POST["session"])){
            $error["session"] = "session not found";
        }
        if(empty($_FILES["justif"]["name"])){
            $error["justif"] = "justification not found";
        }
        $error["id"]=$_POST["session"];
var_dump($error);
        $this->showAbsence($error);
    }
    
    

}