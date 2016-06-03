server '128.199.73.92',user: 'yipl', roles: %w{web app db}

# Directory to deploy
# ===================
set :env, 'staging'
set :deploy_to, '/home/yipl/app/staging'
set :shared_path, '/home/yipl/app/staging/shared'
set :overlay_path, '/home/yipl/app/overlay'
set :app_env, 'production'
set :app_debug, 'false'
set :session_driver, 'file'
set :cache_driver, 'file'
set :app_key, 'mybRtRee32Wn6r98OwpQNR71B3jTeLL9'