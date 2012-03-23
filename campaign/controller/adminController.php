<?php

class adminController extends abstractController {


  /* ************************************************************************ */
  /** This entire controller requires the RIGHT_USER
   __constructor : 
  */
  function adminController() {
    check_user_identity();
    if (!is_admin()) {
      error("Permission Denied on adminController");
      not_found();
    }
  }
  

  /* ************************************************************************ */
  /** Get the list of user accounts */
  function indexAction() {
    global $view;
    render("adminmenu");
  }



}

