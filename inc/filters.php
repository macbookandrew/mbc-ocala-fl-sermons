<form>
    <select name="year_filter">
    <?php
    if (mysqli_num_rows($years)) {
        $rowCount = 0;
        while ($row = mysqli_fetch_row($years)) {
            $sermonYear = $row[0];
            $isSelected = "";
            if (strtolower($sermonYear) == strtolower($_SESSION["sermons_yearFilter"])) $isSelected = " selected";
            echo '<option value="'.$sermonYear.'"'.$isSelected.'>'.$sermonYear.'</option>';
        }
    }
    ?>
</select>

<select name="speaker_filter">
    <option value="Pastor Michael West"<?php if ($_SESSION["sermons_speakerFilter"] == "pastor michael west") echo " selected"; ?>>Pastor Michael West</option>
    <optgroup label="Other Speakers">
        <option value="all"<?php if ($_SESSION["sermons_speakerFilter"] == "all") echo " selected"; ?>>All Speakers</option>
        <?php
        if (mysqli_num_rows($speakers)) {
            $rowCount = 0;
            while ($row = mysqli_fetch_row($speakers)) {
                $sermonSpeaker = $row[0];
                $isSelected = "";

                if ($sermonSpeaker != "Pastor Michael West") {
                    if (strtolower($sermonSpeaker) == strtolower($_SESSION["sermons_speakerFilter"])) $isSelected = " selected";
                    echo '<option value="'.$sermonSpeaker.'"'.$isSelected.'>'.$sermonSpeaker.'</option>';
                }
            }
        }
        ?>
    </optgroup>
</select>

<input type="submit" value="Apply Filters &raquo;" />
</form>
