# #require 'faraday'

# conn = Faraday.new(:url => 'http://http://hearthstoneopen.com/') do |faraday|
#   faraday.request  :url_encoded             # form-encode POST params
#   faraday.response :logger                  # log requests to STDOUT
#   faraday.adapter  Faraday.default_adapter  # make requests with Net::HTTP
# end

# conn.post '/testchat/editor.php', { newfile: 'abc', editfile: "true" } 

require 'net/http'
require 'mharris_ext'

body = File.read("index.php")

page = File.read("page.php")
page = "?>\n#{page}\n<?php"

js = File.read("chat.js")

body = body.gsub("PAGEHERE",page)
body = body.gsub("JSHERE",js)

uri = URI('http://hearthstoneopen.com/testchat/editor.php')
res = Net::HTTP.post_form(uri, 'newfile' => body, 'editfile' => 'true')
puts res.body

File.create "examples/combined_output.php",body