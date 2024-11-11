<?php 
    class conexion{
        public function getconection(){
            //$this->cn=mysqli_connect("localhost","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
            $this->cn=mysqli_connect("demos.jyldigital.com","jyldigit_userw","jhvxQzU+i+h.","jyldigit_widget");
            return $this->cn;
        }
    }
?>