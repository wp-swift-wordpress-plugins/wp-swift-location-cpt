# wp-swift-location-cpt


### Usage

Example php output of location CPTs.

```php

<?php 

$posts = get_posts(array(
    'posts_per_page'    => -1,
    'post_type'         => 'location', 
));

if( $posts ): ?>
    
    <div class="grid-x" id="locations-grid">
        
    <?php foreach( $posts as $post ): 
        
        setup_postdata( $post );
        
        ?>
        <div class="cell auto">
            <h5><?php the_title(); ?></h5>

            <?php if ( get_field('address') ) : ?>
                <div class="address">
                    <?php echo get_field('address'); ?>
                </div>
            <?php endif; ?>

            <?php if ( have_rows('contact_numbers') ) : ?>
            
                <div class="phone-number-groups">

                    <?php while( have_rows('contact_numbers') ) : the_row();
                
                        $label = get_sub_field('label'); 
                        $number = get_sub_field('number');
                        ?>
                        <div class="phone-number-group">
                            <span class="phone-label"><?php echo $label; ?>: </span>
                            <span class="phone-number"><?php echo wp_swift_format_number_link($number); ?></span>
                        </div>
                
                    <?php endwhile; ?>
                    <div class="appoint">(By Appointment Only)</div>

                </div>
            
            <?php endif; ?>
        </div>
    
    <?php endforeach; ?>
    
    </div>
    
    <?php wp_reset_postdata(); ?>

<?php endif; ?>
```
