<?php
/**
 * Plugin Name: MBC Ocala FL Sermons
 * Plugin URI: https://github.com/macbookandrew/mbc-ocala-fl-sermons
 * Description: Add sermons capability
 * Version: 1.0.0
 * Author: AndrewRMinion Design
 * Author URI: https://andrewrminion.com
 * Copyright: 2017 AndrewRMinion Design

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No access allowed' );

require_once( 'inc/functions.php' );

/**
 * Register assets
 */
function mbc_sermon_assets() {
    wp_register_style( 'mbc-sermons', plugin_dir_url( __FILE__ ) . 'css/sermons.css' );
    wp_register_script( 'mbc-sermons', plugin_dir_url( __FILE__ ) . 'js/sermons.js', array( 'jquery' ), NULL, true );
    wp_add_inline_script( 'mbc-sermons', 'var mbcMediaPlayerDir = "' . plugin_dir_url( __FILE__ ) . '";', 'before' );
}
add_action( 'wp_enqueue_scripts', 'mbc_sermon_assets' );

/**
 * Display sermons archive
 * @return string HTML string
 */
function mbc_sermons_shortcode() {
    require_once('inc/mysql.php');
    require_once('inc/queries.php');
    wp_enqueue_style( 'mbc-sermons' );
    wp_enqueue_script( 'mbc-sermons' );

    ob_start();
    include('inc/filters.php');

    if (!mysqli_num_rows($all_sermons)) {
        echo '<div style="text-align: center; font-style: italic;">There are no sermons to display.</div>';
    } else {

        $rowCount = 0;
        $prevMonth = 0;
        $prevYear = 0;

        while ($row = mysqli_fetch_row($all_sermons)) {
            $sermonID          = $row[0];
            $sermonTitle       = $row[1];
            $sermonDescription = $row[2];
            $sermonDate        = $row[3];
            $sermonServiceTime = $row[4];
            $sermonSpeaker     = $row[5];
            $sermonAudioFile   = $row[6];
            $sermonVideoFile   = $row[7];

            $sermonAudioFileSize = round(((filesize("wp-content/uploads/sermons/".$sermonAudioFile) / 1024) / 1024));

            $rowColor = ($rowCount % 2) ? "#eeeeee" : "#ffffff";

            if ($prevMonth != date("n", strtotime($sermonDate))) {
                $prevMonth = date("n", strtotime($sermonDate));
                echo '<div class="month-header">' . date("F Y", strtotime($sermonDate)) . '</div>';
            } ?>

            <div class="individual-sermon">

                <div class="listen-now-container">
                    <?php if (strlen($sermonAudioFile)) { ?>
                        <a class="sermon-link listen" href="#" onclick="popAudioWin('<?php echo $sermonID; ?>'); return false;">Listen Now</a>
                    <?php } ?>
                    <?php if (strlen($sermonVideoFile)) { ?>
                        <a class="sermon-link watch" href="#" onclick="popVideoWin('<?php echo $sermonID; ?>'); return false;">Watch Now</a>
                    <?php } ?>
                </div><!-- .listen-now-container -->
                <p class="sermon-info"><a class="sermon-title"><?php echo $sermonTitle; ?></a> &mdash; <?php echo $sermonSpeaker; ?><br/>
                    <span class="smaller"><?php echo $sermonServiceTime; ?>, <?php echo date("F j, Y", strtotime($sermonDate)); ?></span>
                </p>

                <div class="mediaSlide" style="display: none;">
                    <a class="sermon-link download" href="<?php echo plugin_dir_url( __FILE__ ) . '/inc/sermon-download.php?file=' . $sermonAudioFile; ?>">Download MP3</a> <span class="smaller">(<?php echo $sermonAudioFileSize; ?>MB)</span>

                    <?php if (strlen($sermonDescription)) { ?>
                        <span class="sermon-notes">Sermon Notes: <?php echo $sermonDescription; ?></span>
                    <?php } ?>

                </div><!-- .mediaSlide -->

            </div><!-- .individual-sermon -->
            <?php
            $rowCount++;
        } // end while loop
    }

    return ob_get_clean();
}
add_shortcode( 'mbc_sermons_archive', 'mbc_sermons_shortcode' );
