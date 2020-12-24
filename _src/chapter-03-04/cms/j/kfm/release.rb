#!/usr/bin/env ruby
# This script performs some operations to make KFM release ready

require 'fileutils'
include FileUtils
# Remove .svn directories
rm_rf Dir.["**/.svn/"]

# Remove some plugins
chdir("plugins") do 
  rm_rf(%w{
      codepress 
      return_thumbnail 
      logout
      codepress 
      return_url 
      return_thumbnail 
      return_file_id 
      return_directory
  })
end

# And last but not least, remove this script
rm_f %w{release.rb release.sh}
