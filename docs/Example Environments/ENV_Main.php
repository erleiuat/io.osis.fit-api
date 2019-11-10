<?php

class ENV_Main {

    const error_reports = E_ALL; // E_ERROR / E_ALL
    const timezone = "Europe/Zurich";
    const cors = "http://localhost:8080";
    const log_levels = ['trace', 'debug', 'info', 'warn', 'error', 'fatal'];
    const encryption = PASSWORD_BCRYPT;
    
    const base = "osis-api";
    const path_core = "src/Core";
    const path_class = "src/Class";
    const path_script = "src/Script";
    const path_plugin = "src/Plugin";
    const path_library = "src/Library";

}
