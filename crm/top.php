<?php

?>

<!DOCTYPE html>
<html lang="en">
 
    
    <head>
        <title>Sun Gold Tiles Management System</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/fullcalendar.css" />
        <link rel="stylesheet" href="css/sungold-style.css" />
        <link rel="stylesheet" href="css/sungold-media.css" />
        <link rel="icon" type="image/png" href="img/logo.png" />
    </head>
        <meta charset="utf-8" />
        <title></title>
 
    <body>
        
        <!--Header-part-->
        <div id="header" class="navbar navbar-inverse">
              <ul class="nav" style="padding-top: 10px;">
                 <li class=""><a title="" href="#"><img src="img/login_logo.png" width="17"/> <span class="text" style="padding-left: 5px;">SUN GOLD TILES MANAGEMENT SYSTEM</span></a></li>
            </ul>
        </div>
        <!--close-Header-part-->
        <!--top-Header-menu-->
        <div id="user-nav" class="navbar navbar-inverse">
            <ul class="nav">
                 <li class=""><a title="" href="#"><i class="icon icon-user"></i> <span class="text">Welcome, &nbsp;<?=$Sync_id?> &nbsp;<?=$login_txt?></span></a></li>
                <li class=""><a title="" href="logout.php"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
            </ul>
        </div>

        <!--close-top-Header-menu-->
        <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
            <ul>
                <li><a href="index.php"><i class="icon icon-home"></i> <span>Home</span></a></li>
                <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Accounts</span></a>
                    <ul>
                        <li><a href="account_list.php">Account List</a></li>
                         <li><a href="notification board.php">Notification Board</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-calendar"></i> <span>Company</span></a>
                    <ul>
                        <li><a href="company_list.php">Details</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-tags"></i> <span>Suppliers</span></a>
                    <ul>
                        <li><a href="supplier_list.php">List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-briefcase"></i><span>Materials</span></a>
                    <ul>
                        <li><a href="material_list.php">Materials List</a></li>
                        <li><a href="category_list.php">Category List</a></li>
                        <li><a href="unit_list.php">Unit List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-arrow-up"></i> <span>Builders</span></a>
                    <ul>
                        <li><a href="builder_list.php">List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-folder-open"></i> <span>Projects</span></a>
                    <ul>
                        <li><a href="project_list.php">List</a></li>
                        <li><a href="project_info_list.php">Project Information</a></li>
                        <li><a href="material_to_site.php">Material to Site</a></li>
                        <li><a href="parent_project_list.php">Parent Project List</a></li>
                        <li><a href="project_evaluation_live.php">Project Evaluation</a></li>
                        <li><a href="project_evaluation_live.php">Project Evaluation (Comp)</a></li>
                    </ul>
                </li>
                  <li class="submenu"> <a href="#"><i class="icon icon-folder-open"></i> <span>Order</span></a>
                    <ul>
                        <li><a href="order_list.php">Order List</a></li>
                        <li><a href="purchase_order.php">Purchase Order</a></li>
                        <li><a href="order_notice_list.php">Instructions List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-list"></i> <span>Report</span></a>
                    <ul>
                        <li><a href="report_inventory.php">Inventory</a></li>
                        <li><a href="report_payroll.php">Payroll</a></li>
                        <li><a href="report_project.php">Project Status</a></li>
                        <li><a href="report_supplier.php">Supplier</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-circle-arrow-down"></i> <span>Backup</span></a>
                    <ul>
                        <li><a href="backup_list.php">List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-user"></i> <span>Employees</span></a>
                    <ul>
                        <li><a href="employee_list.php">List</a></li>
                        <li><a href="team_list.php">Team List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-edit"></i> <span>Job Management</span></a>
                    <ul>
                        <li><a href="job_list.php">Time Table</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-shopping-cart"></i> <span>Payroll</span></a>
                    <ul>
                        <li><a href="payroll_list.php">List</a></li>
                        <li><a href="allowance_list.php">Allowance List</a></li>
                        <li><a href="transaction_list.php">Transaction List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-circle-arrow-left"></i> <span>Invoice Management</span></a>
                    <ul>
                        <li><a href="inv_manage_list.php">Invoice List</a></li>
                        <li><a href="inv_manage_proj_invoice.php">Project Invoice</a></li>
                        <li><a href="inv_manage_details.php">Details</a></li>
                        <li><a href="retention_list.php">Retention List</a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="icon icon-cog"></i> <span>Tender Management</span></a>
                    <ul>
                        <li><a href="tender_manage_list.php">Tender List</a></li>
                    </ul>
                </li>
            </ul>
        </div>
      

    </body>
</html>