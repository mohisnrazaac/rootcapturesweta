 <!-- Modal -->
 <div class="modal fade customNuw" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Recent Activities</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
              </div>
              <form method="post">
                <div class="modal-body">
                <table id="zero-config2" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Activity</th>
                            <th class="text-center" scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $SQLGetLogs = $odb -> query("SELECT * FROM `recent_activities` ORDER BY `recent_activities`.`datetime` DESC");
                        while($getInfo = $SQLGetLogs -> fetch(PDO::FETCH_ASSOC))
                        {
                           if(isset($getInfo['user_id']) && $getInfo['user_id'] != Null)
                           {
                            echo '<tr><td>'.$user->getUsernameByid($odb,$getInfo['user_id']).' '.$getInfo['activities'].'</td><td><center>'.$getInfo['datetime'].'</center></td></tr>';
                           }
                           else
                           {
                            echo '<tr><td>'.$getInfo['activities'].'</td><td><center>'.$getInfo['datetime'].'</center></td></tr>';
                           }
                           
                        }
                            
                        ?>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="flaticon-cancel-12"></i> Discard </button>
                </div>
              </form>
            </div>
          </div>
    </div>

        <!-- Modal Ends -->