<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">

      <!-- Display the user's first name or just 'Hi' when sidebar is collapsed -->
      <li class="user-info">
        <a href="#">
          <?php
          // Assuming you have a variable that stores whether the sidebar is collapsed
          $firstname = $this->session->userdata('firstname');
          // Check if sidebar is collapsed (you might need to use a class or JS to check this)
          $sidebar_collapsed = isset($sidebar_collapsed) ? $sidebar_collapsed : false;  // Replace with actual check
          if ($sidebar_collapsed) {
            echo "Hi";
          } else {
            echo "Hello, " . (null !== $firstname ? htmlspecialchars($firstname) : 'Guest');
          }
          ?>
        </a>
      </li>

      <li id="dashboardMainMenu">
        <a href="<?php echo base_url('dashboard') ?>">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>

      <?php if (in_array('createBrand', $user_permission) || in_array('updateBrand', $user_permission) || in_array('viewBrand', $user_permission) || in_array('deleteBrand', $user_permission)): ?>
        <li id="brandNav">
          <a href="<?php echo base_url('Items/') ?>">
            <i class="fa fa-cubes"></i> <span>Items</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)): ?>
        <li id="categoryNav">
          <a href="<?php echo base_url('Category/') ?>">
            <i class="fa fa-th"></i> <span>Category</span>
          </a>
        </li>
      <?php endif; ?>

      <!-- <?php if (in_array('createStore', $user_permission) || in_array('updateStore', $user_permission) || in_array('viewStore', $user_permission) || in_array('deleteStore', $user_permission)): ?>
        <li id="storeNav">
          <a href="<?php echo base_url('Warehouse/') ?>">
            <i class="fa fa-institution"></i> <span>Warehouse</span>
          </a>
        </li>
      <?php endif; ?> -->

      <?php if (in_array('createAttribute', $user_permission) || in_array('updateAttribute', $user_permission) || in_array('viewAttribute', $user_permission) || in_array('deleteAttribute', $user_permission)): ?>
        <li id="attributeNav">
          <a href="<?php echo base_url('Element/') ?>">
            <i class="fa fa-files-o"></i> <span>Elements</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
        <li class="treeview" id="mainProductNav">
          <a href="#">
            <i class="fa fa-cube"></i>
            <span>Products</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if (in_array('createProduct', $user_permission)): ?>
              <li id="addProductNav"><a href="<?php echo base_url('Products/create') ?>"><i class="fa fa-circle-o"></i> Add Product</a></li>
            <?php endif; ?>
            <?php if (in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
              <li id="manageProductNav"><a href="<?php echo base_url('Products') ?>"><i class="fa fa-circle-o"></i> Manage Products</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>


      <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
        <li class="treeview" id="mainOrdersNav">
          <a href="#">
            <i class="fa fa-dollar"></i>
            <span>Orders</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if (in_array('createOrder', $user_permission)): ?>
              <li id="addOrderNav"><a href="<?php echo base_url('Orders/create') ?>"><i class="fa fa-circle-o"></i> Add Order</a></li>
            <?php endif; ?>
            <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
              <li id="manageOrdersNav"><a href="<?php echo base_url('Orders') ?>"><i class="fa fa-circle-o"></i> Manage Orders</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>
      <?php if ($user_permission): ?>
        <?php if (in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
          <li class="treeview" id="mainUserNav">
            <a href="#">
              <i class="fa fa-users"></i>
              <span>Members</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if (in_array('createUser', $user_permission)): ?>
                <li id="createUserNav"><a href="<?php echo base_url('Members/create') ?>"><i class="fa fa-circle-o"></i> Add Members</a></li>
              <?php endif; ?>

              <?php if (in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
                <li id="manageUserNav"><a href="<?php echo base_url('Members') ?>"><i class="fa fa-circle-o"></i> Manage Members</a></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <?php if (in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
          <li class="treeview" id="mainGroupNav">
            <a href="#">
              <i class="fa fa-recycle"></i>
              <span>Permission</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if (in_array('createGroup', $user_permission)): ?>
                <li id="addGroupNav"><a href="<?php echo base_url('Permission/create') ?>"><i class="fa fa-circle-o"></i> Add Permission</a></li>
              <?php endif; ?>
              <?php if (in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
                <li id="manageGroupNav"><a href="<?php echo base_url('Permission') ?>"><i class="fa fa-circle-o"></i> Manage Permission</a></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <!--  <?php if (in_array('viewReports', $user_permission)): ?>
            <li id="reportNav">
              <a href="<?php echo base_url('reports/') ?>">
                <i class="glyphicon glyphicon-stats"></i> <span>Reports</span>
              </a>
            </li>
          <?php endif; ?> -->


        <?php if (in_array('updateCompany', $user_permission)): ?>
          <li id="companyNav"><a href="<?php echo base_url('Company/') ?>"><i class="fa fa-bank"></i> <span>Company</span></a></li>
        <?php endif; ?>

      <?php endif; ?>
      <!-- user permission info -->
      <li><a href="<?php echo base_url('auth/logout') ?>"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<script>
  $(document).ready(function() {
    // Example: toggle class on sidebar collapse
    $('.sidebar-toggle').on('click', function() {
      // Check if sidebar has class 'collapsed' or similar
      if ($('body').hasClass('sidebar-collapse')) {
        // Sidebar collapsed
        $('.user-info a').text('Hi');
      } else {
        // Sidebar expanded
        var firstname = '<?php echo $this->session->userdata('firstname'); ?>';
        if (firstname) {
          $('.user-info a').text('Hello, ' + firstname);
        } else {
          $('.user-info a').text('Hello, Guest');
        }
      }
    });
  });
</script>