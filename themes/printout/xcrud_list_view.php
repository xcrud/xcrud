<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->table_name?></title>
    <style type="text/css">
    <?php if($this->is_rtl){ ?>
        body{
            direction: rtl;
        }
    <?php } ?>
        h1{
            font-size: 22px;
        }
        table{
            border-spacing: 0;
            margin: 0;
            border-collapse: collapse;
            min-width:100%;
            table-layout:fixed;
            font-size: 12px;
            line-height: 1.5;
            border: 1px solid #eee;
        }
        table td,table th{
            border: 1px solid #eee;
            padding: 2px;
        }
        table th{
            background: #f5f5f5;
        }
    </style>
</head>
<body onload="window.print()">
    <h1>
        <?php echo $this->table_name?>
    </h1>
    <table class="xcrud-list table table-striped table-condensed table-hover table-bordered">
        <thead>
            <?php echo $this->render_grid_head(); ?>
        </thead>
        <tbody>
            <?php echo $this->render_grid_body(); ?>
        </tbody>
        <tfoot>
            <?php echo $this->render_grid_footer(); ?>
        </tfoot>
    </table>
</body>
</html>