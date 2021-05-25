THIS BACKEND IS CUSTOMIZED TO WORK WITH THE NONESENSE SERVER THIS PROJECT IS HOSTED ON.

MOST OF THE SERVER URLS ARE NON-CONFORMING WITH WHAT THE GENERIC BACKEND OFFERS.

ON THIS NOTE, FILES SHOULD NOT BE REPLACED RANDOMLY WITHOUT THOROUGH CHECKS


MODIFICATIONS WERE MADE ON

1) The upload dialog.js was customized to restrict folder navigation tree at "httpd.www" folder; --- accomodated in generic backend

2) The process_uplolad.php was customized to use relative paths to locate folders since absolute paths are treated as relative paths by the server. On $_FILES upload section of the script.
For example. /home/user/httpd.www/tlxonline.com/assets/picture/ would not be rendered from the storages' root as this is a perfect absolute path, instead the whole path would be created entirely using your current working directory as the root. --- Fixed

3) controllers/Auth.php class . remove every form of filepath
