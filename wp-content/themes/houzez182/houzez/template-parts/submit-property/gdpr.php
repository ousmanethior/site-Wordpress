<?php
/**
 * Created by PhpStorm.
 * User: Waqas Riaz
 * Date: 12/12/16
 * Time: 5:51 PM
 * Since v1.5.0
 */
global $is_multi_steps;
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php esc_html_e( 'GDPR Agreement *', 'houzez' ); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row push-padding-bottom">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="gdpr_agreement" style="font-weight: 400;">
                            <input type="checkbox" id="gdpr_agreement" class="form-control" name="gdpr_agreement">
                        <?php echo houzez_option('add-prop-gdpr-label'); ?>
                        </label>
                    </div>
                </div>


                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        
                        <textarea rows="5" readonly="readonly" class="form-control"><?php echo houzez_option('add-prop-gdpr-agreement-content');?></textarea>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

