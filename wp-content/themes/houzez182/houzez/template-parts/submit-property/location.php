<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 18/01/16
 * Time: 5:45 PM
 */
global $hide_add_prop_fields, $required_fields, $is_multi_steps;
$location_dropdowns = houzez_option('location_dropdowns');
$default_country = houzez_option('default_country');
$geo_country_limit = houzez_option('geo_country_limit');
$default_lat = houzez_option('default_lat');
$default_long = houzez_option('default_long');

$houzez_map_system = houzez_get_map_system();

$geocomplete_country = '';
if( $geo_country_limit != 0 ) {
    $geocomplete_country = houzez_option('geocomplete_country');
}
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">


    <div class="add-title-tab">
        <h3><?php esc_html_e( 'Property location', 'houzez' ); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row  push-padding-bottom">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="geocomplete"><?php echo esc_html__( 'Address', 'houzez' ).houzez_required_field( $required_fields['property_map_address'] ); ?></label>
                        <input class="form-control" name="property_map_address" id="geocomplete" placeholder="<?php esc_html_e( 'Enter your property address', 'houzez' ); ?>">
                    </div>
                </div>

                <?php if ($hide_add_prop_fields['postal_code'] != 1) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="zip"><?php esc_html_e( 'Postal Code / Zip', 'houzez' ); ?></label>
                            <input class="form-control" name="postal_code" id="zip" placeholder="<?php esc_html_e( 'Enter your property zip code', 'houzez' ); ?>">
                        </div>
                    </div>
                <?php } ?>

                <?php if ($hide_add_prop_fields['country'] != 1) { ?>
                    <div class="col-sm-6 submit_country_field">
                        <div class="form-group">
                            <label for="country"><?php esc_html_e( 'Country', 'houzez' ); ?></label>
                            <?php if( $location_dropdowns == 'yes' ) { ?>
                                <select name="country_short" id="country" class="selectpicker" data-live-search="true">
                                    <?php
                                    $countries_list = houzez_countries_list();
                                    foreach( $countries_list as $key => $country ):
                                        echo '<option '.selected( $default_country, $key, false ).' value="'.$key.'">'.$country.'</option>';
                                    endforeach;
                                    ?>
                                </select>
                            <?php } else { ?>
                                <input class="form-control" name="country" id="country" placeholder="<?php esc_html_e( 'Enter your property country', 'houzez' ); ?>">
                                <input name="country_short" type="hidden" value="">
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($hide_add_prop_fields['state'] != 1) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="countyState"><?php echo esc_html__( 'County / State', 'houzez' ); ?></label>
                            <?php if( $location_dropdowns == 'yes' ) { ?>
                                <select name="administrative_area_level_1" id="administrative_area_level_1" class="selectpicker" data-live-search="true">
                                    <option selected="selected" value=""><?php esc_html_e('None', 'houzez'); ?></option>
                                    <?php
                                    /* Property State */
                                    $property_state_terms = get_terms (
                                        array(
                                            "property_state"
                                        ),
                                        array(
                                            'orderby' => 'name',
                                            'order' => 'ASC',
                                            'hide_empty' => false,
                                            'parent' => 0
                                        )
                                    );
                                    houzez_hirarchical_options( 'property_state', $property_state_terms, -1);
                                    ?>
                                </select>
                            <?php } else { ?>
                                <input class="form-control" name="administrative_area_level_1" id="countyState" placeholder="<?php esc_html_e( 'Enter your property county/state', 'houzez' ); ?>">
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($hide_add_prop_fields['city'] != 1) { ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="city"><?php echo esc_html__( 'City', 'houzez' ); ?></label>
                        <?php if( $location_dropdowns == 'yes' ) { ?>
                            <select name="locality" id="city" class="selectpicker" data-live-search="true">
                                <option selected="selected" value=""><?php esc_html_e('None', 'houzez'); ?></option>
                                <?php
                                /* Property City */
                                $property_cities_terms = get_terms (
                                    array(
                                        "property_city"
                                    ),
                                    array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => false,
                                        'parent' => 0
                                    )
                                );
                                houzez_hirarchical_options( 'property_city', $property_cities_terms, -1);
                                ?>
                            </select>
                        <?php } else { ?>
                            <input class="form-control" name="locality" id="city" placeholder="<?php esc_html_e( 'Enter your property city', 'houzez' ); ?>">
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

                <?php if ($hide_add_prop_fields['neighborhood'] != 1) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="neighborhood"><?php echo esc_html__( 'Neighborhood', 'houzez' ); ?></label>
                            <?php if( $location_dropdowns == 'yes' ) { ?>
                                <select name="neighborhood" id="neighborhood" class="selectpicker" data-live-search="true">
                                    <option selected="selected" value=""><?php esc_html_e('None', 'houzez'); ?></option>
                                    <?php
                                    /* Property Area */
                                    $property_area_terms = get_terms (
                                        array(
                                            "property_area"
                                        ),
                                        array(
                                            'orderby' => 'name',
                                            'order' => 'ASC',
                                            'hide_empty' => false,
                                            'parent' => 0
                                        )
                                    );
                                    houzez_hirarchical_options( 'property_area', $property_area_terms, -1);
                                    ?>
                                </select>
                            <?php } else { ?>
                                <input class="form-control" name="neighborhood" id="neighborhood" placeholder="<?php esc_html_e( 'Enter your property neighborhood', 'houzez' ); ?>">
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
        <div class="add-tab-row">
            <div class="row">
                <div class="col-sm-12">
                    <label><?php esc_html_e('Drag and drop the pin on map to find exact location', 'houzez'); ?></label>
                </div>
                <div class="col-sm-6">
                    <div class="map_canvas" id="map_canvas" ata-add-lat="<?php echo esc_attr($default_lat); ?>" data-add-long="<?php echo esc_attr($default_long); ?>">
                    </div>
                    <button id="find_coordinates" class="btn btn-primary btn-block"><?php esc_html_e( 'Place the pin in address above', 'houzez' ); ?></button>
                    <a id="reset" href="#" style="display:none;"><?php esc_html_e('Reset Marker', 'houzez');?></a>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="latitude"><?php esc_html_e( 'Google Maps latitude', 'houzez' ); ?></label>
                        <input class="form-control" name="lat" id="latitude" placeholder="<?php esc_html_e( 'Enter google maps latitude', 'houzez' ); ?>">
                    </div>
                    <div class="form-group">
                        <label for="longitude"><?php esc_html_e( 'Google Maps longitude', 'houzez' ); ?></label>
                        <input class="form-control" name="lng" id="longitude" placeholder="<?php esc_html_e( 'Enter google maps longitude', 'houzez' ); ?>">
                    </div>
                    <?php if($houzez_map_system == 'google') { ?>
                    <div class="form-group">
                        <label for="prop_google_street_view"><?php esc_html_e('Google Map Street View', 'houzez'); ?></label>
                        <select name="prop_google_street_view" id="prop_google_street_view" class="selectpicker" data-live-search="false">
                            <option value="hide"><?php esc_html_e('Hide', 'houzez'); ?></option>
                            <option selected value="show"><?php esc_html_e('Show', 'houzez'); ?></option>
                        </select>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
