<?php defined('IN_IA') or exit('Access Denied');?>			</div>
		</div>
	</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include fx_template('common/footer-base', TEMPLATE_INCLUDEPATH)) : (include fx_template('common/footer-base', TEMPLATE_INCLUDEPATH));?>