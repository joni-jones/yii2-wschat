<div id="add-room-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?=Yii::t('app', 'Close'); ?></span>
                </button>
                <h4 class="modal-title"><?=Yii::t('app', 'Add Room'); ?></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="title"><?=Yii::t('app', 'Title'); ?>:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?=Yii::t('app', 'Close'); ?>
                </button>
                <button type="button" id="add-room-btn" class="btn btn-primary"><?=Yii::t('app', 'Add'); ?></button>
            </div>
        </div>
    </div>
</div>