<div class="social-networks">
    <div class="_manage">
        <?php $this->widget('ext.hoauth.widgets.HOAuth', array('onlyIcons' => true, 'route' => 'site')); ?>
    </div>

    <?php
        require_once(dirname(__FILE__).'/../../extensions/hoauth/models/UserOAuth.php');
        $hauth = UserOAuth::model()->getHybridAuth();
        $connectedProviders = $hauth::getConnectedProviders();
    ?>
    <?php if (count($connectedProviders) > 0) { ?>
        <div class="_active">
            Active networks:
            <ul>
            <?php foreach($connectedProviders as $provider) { ?>
                <li>
                <?php
                    echo $provider;
                    echo CHtml::ajaxLink(
                        ' (Remove)', Yii::app()->createUrl(
                           'user/remove-social', array('provider' => $provider)
                        ),
                        array(
                            'type' => 'post',
                            'context' => 'js:this',
                            'success' => 'js:function() {$(this).parent().remove();}'
                        ),
                        array('class' => "remove-social")
                    );
                ?>
                </li>
            <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>
