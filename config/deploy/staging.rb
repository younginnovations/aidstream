server '128.199.73.92',user: 'yipl', roles: %w{web app db}

# Directory to deploy
# ===================
set :env, 'staging'
set :deploy_to, '/home/yipl/tz-aidstream/app/staging'
set :shared_path, '/home/yipl/tz-aidstream/app/staging/shared'
set :overlay_path, '/home/yipl/tz-aidstream/app/overlay'
set :tmp_dir, '/home/yipl/tmp'
set :app_env, 'production'
set :app_debug, 'false'
set :session_driver, 'file'
set :cache_driver, 'file'
set :app_key, 'mybRtRee32Wn6r98OwpQNR71B3jTeLL9'
set :tmp_dir, '/home/yipl/tmp'
