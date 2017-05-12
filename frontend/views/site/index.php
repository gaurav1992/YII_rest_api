<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Rest API CRUD';
?>
<div class="site-index">

    <div class="jumbotron">
        <h3>Users listing</h3>

       
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                
				<table class="table">
					<thead>
					  <tr>
						<th>ID</th>
						<th>UserName</th>
						<th>Email</th>
						<th>Actions</th>
					  </tr>
					</thead>
					<tbody>
					<?php 
					foreach($users as $key=>$user){ ?>		
					  <tr>
						<td><?=@$user->id?></td>
						<td><?=@$user->username?></td>
						<td><?=@$user->email?></td>
						<td><a href="<?="site/view-contact"?>" class="btn btn-success">view contacts</a></td>
					  </tr>
					  <?php  } ?>
					 </tbody>
					 
					 </table>
               
				

                
            </div>
            
        </div>

    </div>
</div>
