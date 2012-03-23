<?php

class indexController extends abstractController {


  /* ************************************************************************ */
  /**
  */
  function indexController() {
  }
  

  /* ************************************************************************ */
  /** show the active campaigns */
  function indexAction() {
    global $view;
    $view["campaign"]=mqlist("SELECT * FROM campaign WHERE datestart<=NOW() AND datestop>=NOW() ORDER BY datestart DESC;");
    render("index");
  }



}

