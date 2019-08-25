<div style="margin-top: 2em;">
    <a data-toggle="collapse" href="#codeDescriptions" role="button" aria-expanded="false" aria-controls="codeDescriptions">
        Show descriptions for codes &darr;
    </a>
</div>
<div class="collapse" id="codeDescriptions">
    <ul class="list-unstyled">
        <?php

        use app\models\Attendance;

        foreach (Attendance::ATTENDANCE_VALID_CODES as $code=> $desc) {
            echo "<li><b style='font-family: monospace; padding-right: 10px;'>$code</b> $desc</li>";
        }
        ?>
    </ul>
</div>