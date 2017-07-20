<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>My Account</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/default.min.css">
    <link rel="stylesheet" href="/static/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="/static/css/jquery.jsonview.min.css">
    <link rel="stylesheet" href="/static/css/model_details.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_mid"><?php echo $_GET['mid']; ?></p>
	</div>
<div class="container">
  <h2><strong>My Account</strong></h2>
  <div class="row">
    <div class="col-sm-3 col-md-2 col-lg-2 col-sm-offset-9 col-md-offset-10 col-lg-offset-10">
      <form action="index.php">
        <button class="btn btn-default"><i class="fa fa-arrow-circle-left fa-6x fa-fw"></i>Back to Model List</button>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="main-content">
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingTwo">
            <h4 class="panel-title">
              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Compile Status
              </a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
            <div id="status-content" class="row panel-body">
              <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="status-span">Created at:</div> <div id="submitted_dt"></div>
                <div class="status-span">Last Modified at:</div> <div id="last_modified_dt"></div>
                <div class="status-span">Last Compiled at:</div> <div id="last_compile_dt"></div>
              </div>
              
              <div class="col-sm-6 col-md-6 col-lg-6"> 
                <div id="compile_status"></div>
                <div id="compile_msg"></div>
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <p class="text-primary">If the model is successfully compiled then you can select one or more query set to evaluate.</p>
                    <p class="text-primary">Otherwise correct your model first:)</p>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" >
                    <select id="evaluate_select" multiple="multiple"></select>
                    <button id="query_info" class="btn btn-default" type="button" data-toggle="modal" data-target="#qinfo">Query Info</button>
                    <!-- Modal -->
                    <div class="modal fade" id="qinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">About the Queries</h4>
                          </div>
                          <div class="modal-body" id="qinfo_body">
                            <table id="qinfo-list-table" class="table table-striped table-hover">
                              <thead>
                                <tr>  
                                  <th>
                                    <i class="fa fa-tag fa-fw"></i>Model Name
                                  </th> 
                                  <th>
                                    <i class="fa fa-flag-checkered fa-fw"></i>Query Set
                                  </th>
                                  <th>
                                    <i class="fa fa-clock-o fa-fw"></i>Last Evaluated Time
                                  </th>
                                  <th>
                                    <i class="fa fa-clock-o fa-fw"></i>Status
                                  </th>
                                  <th>
                                    <i class="fa fa-clock-o fa-fw"></i>Performances or Error Msg
                                  </th>
                                </tr>
                              </thead>

                              <tbody>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" >
                    <button type="button" id="evaluate_btn" class="btn btn-primary">Evaluate Model</button>
                  </div>
                  <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Confirm Evaluation?</h4>
                      </div>
                      <div class="modal-body" id="evaluate-modal-body">
                      </div>
                      <div class="modal-footer">
                        <button type="button" id="confirm-evaluate" class="btn btn-success">Confirm</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>
              </div>     
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Evaluation Results
              </a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div id="evaluation_status" class="panel-body">
              <table id="evaluation-list-table" class="table table-striped table-hover">
                <thead>
                  <tr>  
                    <th>
                      <i class="fa fa-tag fa-fw"></i>Model Name
                    </th> 
                    <th>
                      <i class="fa fa-flag-checkered fa-fw"></i>Query Set
                    </th>
                    <th>
                      <i class="fa fa-clock-o fa-fw"></i>Last Evaluated Time
                    </th>
                    <th>
                      <i class="fa fa-clock-o fa-fw"></i>Status
                    </th>
                    <th>
                      <i class="fa fa-clock-o fa-fw"></i>Performances or Error Msg
                    </th>
                  </tr>
                </thead>

                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingPA">
            <h4 class="panel-title">
              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePA" aria-expanded="false" aria-controls="collapsePA">
                Perturbation Analysis
              </a>
            </h4>
          </div>
          <div id="collapsePA" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPA">
            <div id="pa-content" class="row panel-body">              
              <div class="col-sm-12 col-md-12 col-lg-12"> 
                <div id="compile_status"></div>
                <div id="compile_msg"></div>
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <p class="text-primary">If the model is successfully compiled then you can select one or more query set to evaluate.</p>
                    <p class="text-primary">Otherwise correct your model first:)</p>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    Please refer to <a href="https://www.eecis.udel.edu/~hfang/pubs/tois09.pdf">our paper</a> for details.
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" >
                    <select id="pertub_type_select" multiple="multiple">
                      <option value="1">LV1</option>
                      <option value="3">LV3</option>
                      <option value="4">TN1(constant)</option>
                      <option value="5">TN2(linear)</option>
                      <option value="6">TG1(constant)</option>
                      <option value="10">TG3(constant)</option>
                    </select>
                    <select id="pertub_coll_select" multiple="multiple"></select>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" >
                    <button type="button" id="pa_btn" class="btn btn-primary">Run Perturbation Analysis</button>
                  </div>
                  <div id="pertube-ev-res" class="col-sm-12 col-md-12 col-lg-12">
                    <hr>
                    <h4>Perturbation Evaluation Results</h4>
                    <hr>
                    <select id="pertube-results-select">
                      
                    </select>
                    <div id="draw-pertube-res"> </div>
                  </div>

                  <div class="modal fade" id="PAconfirmModal" tabindex="-1" role="dialog" aria-labelledby="PAModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="PAModalLabel">Confirm?</h4>
                      </div>
                      <div class="modal-body" id="perturb-modal-body">
                      </div>
                      <div class="modal-footer">
                        <button type="button" id="confirm-pa" class="btn btn-success">Confirm</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>
              </div>     
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingThree">
            <h4 class="panel-title">
              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Modify Model
              </a>
            </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
            <div class="panel-body">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#form" aria-controls="form" role="tab" data-toggle="tab">Implementation</a></li>
                <li role="presentation"><a href="#instruction" aria-controls="instruction" role="tab" data-toggle="tab">Instruction</a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content main-content">
                <div role="tabpanel" class="tab-pane fade in active" id="form">
                  <form role="form" id="fform" method="POST" action="/api/play/add_update_model.php" autocomplete="off">
                    <div class="form-group">
                      <label for="mname">Model Name</label>
                      <input type="text" name="mname" class="form-control" id="mname" placeholder="Model Name">
                    </div>
                    <div class="form-group">
                      <label for="mpara">Model Parameters</label>
                      <p class="help-block">This is just a string indicating the model belongs to model name above.</p>
                      <input type="text" name="mpara" class="form-control" id="mpara" placeholder="Model Parameters">
                    </div>
                    <div class="form-group">
                      <label for="mnotes">Model Notes</label>
                      <p class="help-block">You can leave some notes about the model. You can use text between two dollar signs to add math equations.</p>
                      <textarea name="mnotes" class="form-control" id="mnotes" rows="5" placeholder="Model Notes: original authors, publish year, etc."></textarea>
                    </div>
                    <div class="form-group">
                      <label for="mbody">Model Implementation</label>
                      <input type="hidden" name="mbody" class="form-control" id="mbody" placeholder="Model Body">
                      <div id="editor"></div> 
                    </div>
                    <input type="hidden" name="uid" id="uid">
                    <input type="hidden" name="apikey" id="apikey">
                    <input type="hidden" name="mid" id="mid">
                    <button type="submit" id="submit_model" class="btn btn-primary">Submit Modification</button>
                  </form>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="instruction">Cannot get model implementation instruction from server...</div>
              </div>

              <p class="text-center" id="waiting-span">
                <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
              </p>
              
              <div class="scroll-top-wrapper ">
                <span class="scroll-top-inner">
                  <i class="fa fa-2x fa-arrow-circle-up"></i>
                </span>
              </div> 
            </div>
          </div>
        </div>
      </div>
 
    </div>
  </div>

</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>
<script type="text/javascript" src="//cdn.jsdelivr.net/ace/1.2.3/min/ace.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/highlight.min.js"></script>
<script type="text/javascript" src="/static/js/marked.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js"></script>
<script type="text/javascript" src="/static/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="/static/js/jquery.jsonview.min.js"></script>
<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="/static/js/model_details.js"></script>
</body>
</html>
