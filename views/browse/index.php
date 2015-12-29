<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;

$bundle = \humhub\modules\cfiles\Assets::register($this);
$this->registerJsVar('cfilesUploadUrl', $contentContainer->createUrl('/cfiles/browse/upload', [
    'fid' => $folderId
]));
$this->registerJsVar('cfilesZipUploadUrl', $contentContainer->createUrl('/cfiles/zip/upload-zipped-folder', [
    'fid' => $folderId
    ]));
$this->registerJsVar('cfilesDeleteUrl', $contentContainer->createUrl('/cfiles/browse/delete', [
    'fid' => $folderId
]));
$this->registerJsVar('cfilesEditFolderUrl', $contentContainer->createUrl('/cfiles/browse/edit-folder', [
    'id' => '--folderId--'
]));
$this->registerJsVar('cfilesZipFolderUrl', $contentContainer->createUrl('/cfiles/zip/download-zipped-folder', [
    'fid' => '--folderId--'
    ]));
$this->registerJsVar('cfilesMoveUrl', $contentContainer->createUrl('/cfiles/browse/move-files', [
    'init' => 1
]));

?>
<?php echo Html::beginForm(null, null, ['data-target' => '#globalModal']); ?>
<div class="panel panel-default">

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-9" id="fileList">
                <?php echo $fileList; ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-3">

                <div id="progress" class="progress"
                    style="display: none">
                    <div class="progress-bar progress-bar-success"></div>
                </div>

                <ul class="nav nav-pills nav-stacked">
                    <li><span class="fileinput-button btn btn-success">
                            <i class="glyphicon glyphicon-plus"></i> <?php echo Yii::t('CfilesModule.base', '<strong>Add file(s)</strong>');?> <input id="fileupload"
                            type="file" name="files[]" multiple>
                    </span></li>
                    <li class="nav-divider"></li>
                    <li><a class="fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i> <?php echo Yii::t('CfilesModule.base', 'Upload .zip');?> <input id="zipupload"
                            type="file" name="files[]" multiple>
                    </a></li>
                    <li><?php echo Html::a(Yii::t('CfilesModule.base', 'Download .zip'), $contentContainer->createUrl('/cfiles/zip/download-zipped-folder', ['fid' => $folderId])); ?></li>
                    <li><?php echo Html::a(Yii::t('CfilesModule.base', 'Add directory'), $contentContainer->createUrl('/cfiles/browse/edit-folder', ['fid' => $folderId]), array('data-target' => '#globalModal')); ?></li>
                    <?php if ($folderId !== BrowseController::ROOT_ID) : ?>
                        <li><?php echo Html::a(Yii::t('CfilesModule.base', 'Edit directory'), $contentContainer->createUrl('/cfiles/browse/edit-folder', ['id' => $folderId]), array('data-target' => '#globalModal')); ?></li>
                    <?php endif; ?>
                    <li>
                        <?php
                        echo \humhub\widgets\AjaxButton::widget([
                            'label' => Yii::t('CfilesModule.base', 'Delete')." (<span class='chkCnt'></span>)",
                            'tag' => 'a',
                            'ajaxOptions' => [
                                'type' => 'POST',
                                'success' => new yii\web\JsExpression('function(html){ $("#fileList").html(html); showHideBtns();}'),
                                'url' => $contentContainer->createUrl('/cfiles/browse/delete', [
                                    'fid' => $folderId
                                ])
                            ],
                            'htmlOptions' => [
                                'class' => 'selectedOnly filedelete-button',
                                'style' => 'display:none'
                            ]
                        ]);
                        ?>
                    </li>
                    <li>
                        <?php
                        echo \humhub\widgets\AjaxButton::widget([
                            'label' => Yii::t('CfilesModule.base', 'Move')." (<span class='chkCnt'></span>)",
                            'tag' => 'a',
                            'ajaxOptions' => [
                                'type' => 'POST',
                                'success' => new yii\web\JsExpression('function(html){ $("#globalModal").html(html); $("#globalModal").modal("show"); openDirectory(' . $folderId . '); selectDirectory(' . $folderId . ');}'),
                                'url' => $contentContainer->createUrl('/cfiles/browse/move-files', [
                                    'init' => 1,
                                    'fid' => $folderId
                                ])
                            ],
                            'htmlOptions' => [
                                'class' => 'selectedOnly filemove-button',
                                'style' => 'display:none'
                            ]
                        ]);
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php echo Html::endForm(); ?>


<ul id="contextMenuFolder" class="contextMenu dropdown-menu" role="menu"
    style="display: none">
    <li><a tabindex="-1" href="#" data-action='download'><?php echo Yii::t('CfilesModule.base', 'Open');?></a></li>
    <li role="separator" class="divider"></li>
    <li><a tabindex="-1" href="#" data-action='edit'><?php echo Yii::t('CfilesModule.base', 'Edit');?></a></li>
    <li><a tabindex="-1" href="#" data-action='delete'><?php echo Yii::t('CfilesModule.base', 'Delete');?></a></li>
    <li><a tabindex="-1" href="#" data-action='move-files'><?php echo Yii::t('CfilesModule.base', 'Move folder');?></a></li>
    <li><a tabindex="-1" href="#" data-action='zip'><?php echo Yii::t('CfilesModule.base', 'Download zip');?></a></li>
</ul>

<ul id="contextMenuFile" class="contextMenu dropdown-menu" role="menu"
    style="display: none">
    <li><a tabindex="-1" href="#" data-action='download'><?php echo Yii::t('CfilesModule.base', 'Download');?></a></li>
    <li role="separator" class="divider"></li>
    <li><a tabindex="-1" href="#" data-action='delete'><?php echo Yii::t('CfilesModule.base', 'Delete');?></a></li>
    <li><a tabindex="-1" href="#" data-action='move-files'><?php echo Yii::t('CfilesModule.base', 'Move file');?></a></li>
</ul>

<ul id="contextMenuImage" class="contextMenu dropdown-menu" role="menu"
    style="display: none">
    <li><a tabindex="-1" href="#" data-action='download'><?php echo Yii::t('CfilesModule.base', 'Download');?></a></li>
    <li role="separator" class="divider"></li>
    <li><a tabindex="-1" href="#" data-action='show'><?php echo Yii::t('CfilesModule.base', 'Show');?></a></li>
    <li><a tabindex="-1" href="#" data-action='delete'><?php echo Yii::t('CfilesModule.base', 'Delete');?></a></li>
    <li><a tabindex="-1" href="#" data-action='move-files'><?php echo Yii::t('CfilesModule.base', 'Move file');?></a></li>
</ul>

<ul id="contextMenuAllPostedFiles" class="contextMenu dropdown-menu"
    role="menu" style="display: none">
    <li><a tabindex="-1" href="#" data-action='download'><?php echo Yii::t('CfilesModule.base', 'Open');?></a></li>
    <li><a tabindex="-1" href="#" data-action='zip'><?php echo Yii::t('CfilesModule.base', 'Download zip');?></a></li>
</ul>

<div id="hiddenLogContainer" style="display: none">
    <div class="alert alert-danger" style="display: none">
        <ul>
        </ul>
    </div>
    <div class="alert alert-warning" style="display: none">
        <ul>
        </ul>
    </div>
    <div class="alert alert-info" style="display: none">
        <ul>
        </ul>
    </div>
</div>
