<?php
class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }   
    
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
            $this->create();
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
            $this->delete($_POST['id']);
            return;
        }
        
        $users = $this->userModel->getAll();
        $this->view('user', [
            'users' => $users
        ]);
    }
    private function create() {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        
        if (!empty($name) && !empty($email)) {
            $data = [
                'name' => $name,
                'email' => $email,
            ];
            
            $this->userModel->create($data);
        }
        
        $this->redirect('');
    }
    
    private function delete($id) {
        if (!empty($id)) {
            $this->userModel->delete($id);
        }
        
        $this->redirect('');
    }
    
}
?>