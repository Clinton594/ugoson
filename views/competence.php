<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$generic->name?> About </title>
  <?php require_once 'includes/links.php'; ?>
</head>
<body class="bg-triangles">
  <main class="main">
    <div class="container gutter-top">
      <div class="row sticky-parent">
        <?php require_once 'includes/sidebar.php'; ?>
        <div class="col-12 col-md-12 col-xl-9">
          <div class="box shadow pb-0">
            <?php require_once 'includes/header.php'; ?>

            <div class="pb-3">
              <h1 class="title title--h1 title__separate">Competence</h1>
            </div>

            <div class="box-inner box-inner--rounded">
              <div class="row">
                <div class="col-12">
                  <h2 class="title title--h3">Key Competencies</h2>
                  <div class="box box__second">
                    <ul>
                      <li>Advanced Level PHP</li>
                      <li>MySQL Database Management</li>
                      <li>REST APIs Development</li>
                      <li>JavaScript Vanilla (ES6)</li>
                      <li>JavaScript Frameworks (JQuery & Node JS)</li>
                      <li>HTML, XML, SVG & CSS</li>
                      <li>Web Hosting, Unit Testing</li>
                      <li>Windows & Drivers Diagnostic</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="box-inner box-inner--rounded">
              <div class="row">
                <div class="col-12 col-lg-6">
                  <h2 class="title title--h3">Programming Skills</h2>
                  <div class="box box__second">

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>PHP Language</span><span>85%</span></div>
                      </div>
                    </div>

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>MySQL Databases</span><span>90%</span></div>
                      </div>
                    </div>

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>Web Design (HTML, XML, SVG & CSS)</span><span>95%</span></div>
                      </div>
                    </div>

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="97" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>JQuery JS</span><span>97%</span></div>
                      </div>
                    </div>


                  </div>
                </div>

                <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                  <h2 class="title title--h3">-</h2>
                  <div class="box box__second">
                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>Node JS</span><span></span></div>
                      </div>
                      <div class="progress-text"><span>Node JS</span><span>30%</span></div>
                    </div>

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>Java</span></div>
                      </div>
                      <div class="progress-text"><span>Java</span><span>30%</span></div>
                    </div>

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>Solidity</span><span></span></div>
                      </div>
                      <div class="progress-text"><span>Solidity</span><span>25%</span></div>
                    </div>

                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-text"><span>Graphics Designs</span><span></span></div>
                      </div>
                      <div class="progress-text"><span>Graphics Designs</span><span>50%</span></div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <footer class="footer">© <?=date("Y")?> Portfolio</footer>
        </div>
      </div>
    </div>
  </main>
  <?php require_once 'includes/footer.php'; ?>
</body>
</html>
