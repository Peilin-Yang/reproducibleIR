<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/require_login.php"); 

  $username = $_SESSION['username'];

  echo '
  <link rel="stylesheet" href="../static/css/navbar.css">
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="'.SITE.'">TRECCS Summary Judge</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#" id="guide" data-toggle="modal" data-target="#guideModal">Judgement Guide</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle user" data-toggle="dropdown" role="button" aria-expanded="false">'.$username.'<span class="caret"></span></a>
            <ul class="dropdown-menu nav-dropdown-menu" role="menu">
              <li>
                <form action="/treccs_summary_judge/login/logout.php">
                <button type="submit" id="logout" class="btn btn-sm btn-danger" title="Log Out"><i class="fa fa-sign-out fa-fw"></i> Log Out</button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="modal fade" id="guideModal" tabindex="-1" role="dialog" aria-labelledby="guideModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="guideModalLabel">Judgement Guide</h4>
        </div>
        <div class="modal-body">
          <h4>This page describes the judgement criteria of TREC Contextual Suggestion Summary Generation</h4>
          <p>We (UD Infolab Group) propose a structured summary for the attractions we suggest. The four parts are:</p>
          <ul>
            <li><strong>The opening sentence:</strong> which shows the category (as fine as possible) of the attraction</li>
            <li><strong>The web introduction:</strong> which shows the introduction from the web site of the attraction</li>
            <li><strong>The highlighted review:</strong> which shows the promising reviews from other customers</li>
            <li><strong>The concluding sentence:</strong> which shows the similar attractions that you have preference before</li>
          </ul>

          <p>Please note that some parts might be unavailable especially for the concluding sentence</p>

          <hr/>

          <div style="color:red;">
            <p><i>You are asked to judge the relevance level of each part of the summary and also the summary as a whole</i></p>
            <p>Please follow the principles mentioned below:</p>
            <ul>
              <li><strong>The relevance should be only decided by whether the summary contains useful information about the attraction</strong></li>
              <li><strong>No personal preference should be involved during the judgement</strong></li>
            </ul>
          </div>

          <h3>Thank you for your generous help!</h3>
        </div>
      </div>
    </div>
  </div>
  ';
?>