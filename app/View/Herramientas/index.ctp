<?php
// app/View/Inmuebles/view2top.ctp
$this->extend('/Common/view2top');

$this->set('title_for_layout', 'Herramientas');

$this->start('header');
?>
<script type="text/javascript">
</script>
<?php $this->end(); ?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Descargas</h3>
	</div>
	<div class="panel-body">
		<ul class="list-unstyled">
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Generador de planos', 'http://www.alfainmo.com/descargas/generarPlanos.exe', array('escape' => false)); ?></li>
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Hoja de visita', 'http://www.alfainmo.com/descargas/hoja-de-visita-mexico.docx', array('escape' => false)); ?></li>
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Ficha de escaparate de M&eacute;xico', 'http://www.alfainmo.com/descargas/ficha-escaparate-mexico.docx', array('escape' => false)); ?></li>
		</ul>
		<p></p>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Publicidad</h3>
	</div>
	<div class="panel-body">
		<ul class="list-unstyled">
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Publicidad', 'publicidad/', array('escape' => false)); ?></li>
		</ul>
	</div>
</div>
<?php if (!$profile['is_consultor']) { ?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Listadores</h3>
	</div>
	<div class="panel-body">
		<ul class="list-unstyled">
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Listado de inmuebles', 'lst_inmuebles/', array('escape' => false)); ?></li>
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Listado de demandantes', 'lst_demandantes/', array('escape' => false)); ?></li>
		</ul>
		<ul class="list-unstyled">
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('Ayuda Listador en Excel 2003', 'http://www.alfainmo.com/descargas/listador_excel_2003.pdf', array('escape' => false, 'target' => '_blank')); ?></li>
		</ul>
	</div>
</div>
<?php } ?>
<!--
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">CRM - Informes</h3>
	</div>
	<div class="panel-body">
		<ul class="list-unstyled">
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('CRE de Captación', 'cre_captacion/', array('escape' => false)); ?></li>
			<li><i class="glyphicon glyphicon-list"></i> <?php echo $this->Html->link('CRE de Venta', 'cre_venta/', array('escape' => false)); ?></li>
		</ul>
	</div>
</div>
-->