let user = "ubuntu";
module.exports = {
    apps: [
        {
            name       : "default",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=default --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/default.out.log",
            error_file : "./storage/logs/workers/default.error.log",
            user       : user,
        },
        {
            name       : "ringout",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=ringout --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/ringout.out.log",
            error_file : "./storage/logs/workers/ringout.error.log",
            user       : user,
        },
        {
            name       : "ringout-faxes",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=ringout --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/ringout-faxes.out.log",
            error_file : "./storage/logs/workers/ringout-faxes.error.log",
            user       : user,
        },
        {
            name       : "ringout-socket",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=ringout-socket --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/ringout-socket.out.log",
            error_file : "./storage/logs/workers/ringout-socket.error.log",
            user       : user,
        },
        {
            name       : "laravel-echo-server",
            script     : "/bin/bash",
            args       : ["-c", "laravel-echo-server start"],
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/laravel-echo-server.out.log",
            error_file : "./storage/logs/workers/laravel-echo-server.error.log",
            user       : user,
        },
        {
            name       : "officeally",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=officeally --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 2,
            watch      : false,
            out_file   : "./storage/logs/workers/officeally.out.log",
            error_file : "./storage/logs/workers/officeally.error.log",
            user       : user,
        },
        {
            name       : "officeally-billing",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=officeally-billing --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/officeally-billing.out.log",
            error_file : "./storage/logs/workers/officeally-billing.error.log",
            user       : user,
        },
        {
            name       : "parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=parser --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/parser.out.log",
            error_file : "./storage/logs/workers/parser.error.log",
            user       : user,
        },
        {
            name       : "payments",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=payments --timeout=300 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/payments.out.log",
            error_file : "./storage/logs/workers/payments.error.log",
            user       : user,
        },
        {
            name       : "single-parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=single-parser --timeout=600 --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/single-parser.out.log",
            error_file : "./storage/logs/workers/single-parser.error.log",
            user       : user,
        },
        {
            name       : "tridiuum",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work redis_long --queue=tridiuum --sleep=3 --tries=1 --timeout=3600",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/tridiuum.out.log",
            error_file : "./storage/logs/workers/tridiuum.error.log",
            user       : user,
        },
        {
            name       : "tridiuum-long",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work redis_long --queue=tridiuum-long --sleep=3 --tries=1 --timeout=3600",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/tridiuum-long.out.log",
            error_file : "./storage/logs/workers/tridiuum-long.error.log",
            user       : user,
        },
        {
            name       : "tridiuum-availability",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=tridiuum-availability --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/tridiuum-availability.out.log",
            error_file : "./storage/logs/workers/tridiuum-availability.error.log",
            user       : user,
        },
        {
            name       : "tridiuum-parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=tridiuum-parser --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/tridiuum-parser.out.log",
            error_file : "./storage/logs/workers/tridiuum-parser.error.log",
            user       : user,
        },
        {
            name       : "daily-parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=daily-parser --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/daily-parser.out.log",
            error_file : "./storage/logs/workers/daily-parser.error.log",
            user       : user,
        },
        {
            name       : "visits-parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=visits-parser --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/visits-parser.out.log",
            error_file : "./storage/logs/workers/visits-parser.error.log",
            user       : user,
        },
        {
            name       : "oa-retry-parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work database --queue=oa-retry-parser --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/oa-retry-parser.out.log",
            error_file : "./storage/logs/workers/oa-retry-parser.error.log",
            user       : user,
        },
        {
            name       : "restart-parser",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=restart-parser --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/restart-parser.out.log",
            error_file : "./storage/logs/workers/restart-parser.error.log",
            user       : user,
        },
        {
            name       : "zip-archive",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=zip-archive --sleep=3 --tries=1 --timeout=0",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/zip-archive.out.log",
            error_file : "./storage/logs/workers/zip-archive.error.log",
            user       : user,
        },
        {
            name       : "workers-default",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=workers-default --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/workers-default.out.log",
            error_file : "./storage/logs/workers/workers-default.error.log",
            user       : user,
        },
        {
            name       : "workers-command",
            interpreter: "php",
            script     : "artisan",
            args       : "queue:work --queue=workers-command --sleep=3 --tries=1",
            exec_mode  : "fork",
            instances  : 1,
            watch      : false,
            out_file   : "./storage/logs/workers/workers-command.out.log",
            error_file : "./storage/logs/workers/workers-command.error.log",
            user       : user,
        },
    ]
}