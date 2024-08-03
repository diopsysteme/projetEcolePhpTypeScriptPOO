

// namespace Controller;

// use App\App;
// use Core\Controller;
// use Core\File;
// use Core\Session;
// use Core\Validator;
// use Core\Validator2;
// use Entity\ClientEntity;
// use Model\DetteModel;

// class ClientController extends Controller
// {
//     private $clientModel;
//     private $articleModel;
//     public function __construct(Session $session, Validator2 $validator, File $file)
//     {
//         parent::__construct($session,$validator,$file);
//         $this->clientModel = App::getInstance()->getModel("Client");
//         $this->articleModel = App::getInstance()->getModel("Article");
//     }

//     public function listAdd($num, $article = null)
//     {
//         $clients = $this->session::get("client");
//         $entity = $this->clientModel->getEntityClass();
//         $entityInstance = \Core\Factory::instantiateClass($entity);
//         $entityInstance->unserialize($clients);

//         if ($num != $entityInstance->telephone) {
//             echo "Forbiden";
//             die();
//         }
//         $panier = new \Entity\PanierEntity($entityInstance);
//         // var_dump($panier->serialize());
//         $articles = $this->articlesPanier();
//         $this->renderView('ajoutDette', ['clients' => $entityInstance, "article" => $article, 'articles' => $articles]);
//     }
//     public function ajoutPanier($id)
//     {
//         if (isset($_POST["searchProd"])) {
//             $article = $this->articleModel->searchByAttribute("libelle", $_POST["article_search"]);
//         } elseif (isset($_POST["addToCart"])) {
//             $articlee = "\Entity\ArticleEntity";
//             $articlee = new \ReflectionClass($articlee);
//             $art = $articlee->newInstance();
//             $art->setArticle($_POST["id"], $_POST["addToCart"], $_POST["quantity"], $_POST["pu"], $_POST["qtstock"]);
//             if ($this->session::get("articles") == null) {
//                 $arts[] = $art->serialize();
//                 $this->session::set("articles", $arts);
//             } else {

//                 $arts = $this->session::get("articles");
//                 // var_dump($arts);
//                 $newline = true;
//                 $artPans = $this->articlesPanier();
//                 foreach ($artPans as &$artPanier) {
//                     if ($artPanier->id == $art->id) {
//                         $artPanier->quantitevendu += $art->quantitevendu;
//                         $newline = false;
//                         break;
//                     }
//                 }
//                 if ($newline) {
//                     $arts[] = $art->serialize();
//                     $this->session::set("articles", $arts);
//                 } else {
//                     $arts2=[];
//                     foreach ($artPans as $artPanier2) {
//                         $arts2[] = $artPanier2->serialize();
//                     }
//                     $this->session::set("articles", $arts2);
//                     // var_dump($this->session::get("articles"));
//                 }
               
//             }
//             echo "ajout";
//         }
//         $articles = $this->articlesPanier();
//         $this->listAdd($id, $article[0]);
//     }

//     public function searchClientByPhone($telephone)
//     {
//         $clients = $this->clientModel->searchByAttribute('telephone', $telephone, ClientEntity::class);
//         $this->renderView('ajoutDette/', ['clients' => $clients]);
//     }

//     public function createClient($data)
//     {
//         $this->clientModel->save($data);
//     }
//     public function index()
//     {
//         $clients = $this->clientModel->all();
//         $this->renderView('dashboard', ['clients' => $clients]);
//     }
//     public function store()
//     {
//         if (isset($_POST['register'])) {

//             $data = [
//                 'nom' => $_POST['nom'],
//                 'prenom' => $_POST['prenom'],
//                 'mail' => $_POST['mail'],
//                 'telephone' => $_POST['telephone'],
//                 'photo' => $_FILES["filephoto"]["name"],
//                 "password" => password_hash("Passer123", PASSWORD_DEFAULT),
//                 "observation" => "Nouveau client"
//             ];
//             // var_dump($data);
//             $img = $_FILES["filephoto"]["name"];
//             $img_tmp = $_FILES["filephoto"]["tmp_name"];
        
//             // Define validation rules
//             $rules = [
//                 'nom' => 'required',
//                 'prenom' => 'required',
//                 'mail' => 'required|email',
//                 'telephone' => 'required|phone',
//                 'photo' => 'required|file'
//             ];
        
//             // Optionally, you can define custom error messages
//             $customMessages = [
//                 'nom' => [
//                     'required' => 'Le champ nom est requis.',
//                     'unique' => 'Le champ nom doit être unique.'
//                 ],
//                 'prenom' => [
//                     'required' => 'Le champ prénom est requis.'
//                 ],
//                 'mail' => [
//                     'required' => 'Le champ email est requis.',
//                     'email' => 'Veuillez entrer une adresse email valide.'
//                 ],
//                 'telephone' => [
//                     'required' => 'Le champ téléphone est requis.',
//                     'phone' => 'Veuillez entrer un numéro de téléphone valide.'
//                 ],
//                 'photo' => [
//                     'required' => 'Le champ photo est requis.',
//                     'file' => 'Le champ photo doit être un fichier valide.'
//                 ]
//             ];
        
//             // Validate data
//             var_dump($this->file);
//             $validator =$this->validator->validateData($data, $rules,$customMessages);
//             // var_dump($validator->passes());
//             if ($validator->passes()) {
//                 // var_dump($validator->passes());
                
//                 $uploadMessage = $this->file->upload($_FILES["filephoto"],"photo".time().".jpg");
//                 $data["photo"]="photo".time().".jpg";
//                 $this->createClient($data);
//                 $this->renderView('dashboard');
//             } else {

//                 $errors = $validator->errors();
//                  var_dump($errors);
//                 $this->renderView('dashboard', ['errors' => $errors]);
//             }
//         } elseif (isset($_POST['searchClient'])) {
//             $datad = $this->clientModel->infosClientDebt($_POST['telephone']);

//             if (!empty($datad)) {
//                 $this->session::set("client", $datad[0]->serialize());
//                 // var_dump("sss<br>", $this->session::get("client"));
//                 $clientInfo = $datad[0];
//                 // var_dump($datad[0]->id);
//                 $dd = $this->clientModel->belongsTo(DetteModel::class, "idclient", $datad[0]->id);
//                 // var_dump($dd);

//                 $this->renderView('dashboard', ["datad" => $datad]);
//             } else {
//                 $this->renderView('dashboard', ["client" => null]);
//             }
//         } elseif (isset($_POST["ajoutDette"])) {

//             $clients = $this->clientModel->searchByAttribute('telephone', $_POST["ajoutDette"], ClientEntity::class);
//             $this->renderView('ajoutDette', ['clients' => $clients]);
//         }
//     }

//     public function listdette($var)
//     {
//         // var_dump($var);
//         $clients = $this->session::get("client");
//         $entity = $this->clientModel->getEntityClass();
//         $entityInstance = \Core\Factory::instantiateClass($entity);
//         $entityInstance->unserialize($clients);
//         if (!$clients) {
//             $this->renderView('error');
//             return;
//         }
//         $dettes = $this->clientModel->hasMany(DetteModel::class, "idclient", $entityInstance->id);
//         // var_dump($dettes);
//         $this->renderView('dette/dette', ['clients' => $entityInstance, "dettes" => $dettes]);
//     }
// }
