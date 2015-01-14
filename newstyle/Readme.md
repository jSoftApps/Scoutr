Scoutr v 2.0.0
==============
Copyright (C) 2014 jSoft Apps All Rights Reserved

Disclaimers and License Info
============================
Scoutr uses a heavily modified version of SB Admin 2, an administration theme created by Start Bootstrap. SB Admin 2 is licensed under the Apache 2.0 license. For more information, go to licenses/apache.txt

Per-asset licenses can be found in their respective folders under "assets/plugins"

Scoutr's source code is licensed under the CC BY-NC-SA 4.0 license. This essentially means that you can use Scoutr for whatever you want as long as it is for non commercial purposes, and proper credit is given to jSoft Apps and Scoutr. For more information, go to licenses/ccbyncsa.txt

THE PROGRAM IS DISTRIBUTED IN THE HOPE THAT IT WILL BE USEFUL, BUT WITHOUT ANY WARRANTY. IT IS PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.

IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW THE AUTHOR WILL BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF THE AUTHOR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.

What is Scoutr?
===============
Scoutr is a free FRC scouting application. It's goal is to provide an easy, fast, and reliable scouting solution to all FRC teams. All of the scouting data is shared, so everyone can contribute to the database, which promotes teamwork between teams, and the sharing of ideas. Scoutr pulls all match data directly from the FRC's twitter account, so per-match scouting has been eliminated, giving teams an opportunity to actually watch games and cheer their team on instead of paying attention to statistics.

How Can I Help?
===============
We're currently looking for coders proficiant in PHP and MySQL, so if you know anyone that's good at that, tell them to email James Kienle (james@jsoftapps.com)
We're also looking for people to help test bugs. If you want to help out that way, also email James and he'll set you up with an account.

Directory Breakdown
===================
|- Root
 |- assets
  |- css
  |- img
  |- js
  |- plugins
   |- bootstrap
   |- bootstrap-social
   |- datatables
   |- datatables-plugins
   |- datatables-responsive
   |- flot
   |- flot.tooltip
   |- font-awesome
   |- holderjs
   |- jquery
   |- metisMenu
   |- mocha
   |- morrisjs
   |- raphael
 |- config
 |- includes
 
 Assets contains all of the CSS files, JS files, and page/graph plugins. Basically it contains everything that isn't back-end PHP code.
 
 Config contains PHP configuration files. This is the folder that you will want to modify if you're running your own version of Scoutr on your own servers. It also contains the MySQL install files.
 
 Includes contains all back-end PHP files. You will most likely not need to modify the files contained in this folder, as they control all of Scoutr's functions and do not contain any distribution specific information.