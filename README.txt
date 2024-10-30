===Just another survey tool===
Contributors: Li xintao
Donate link: 
Tags: survey, questionnaire, interactive, feedback
Requires at least: 3.5.1
Tested up to: 3.8
Stable tag: 0.9.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

JAST supply a simple way to let your site interactive with the visitor. It's easy to use, but can still do complicate survey questionnaires.

== Description ==
<span style="color:red;">Just another survey tool</span> is a plugin which you can add survey questionnaire to wordpress based web site. Visitors can do the questionnaire by popup window or normal post page, the results will store in database. 

Other remarkable features include:

<ul>
	<li>Easy to use, can add a questionnaire in 5 minutes. But still can handle very complicate questionnaire.</li>
	<li>Chart and table mode to show survey results. </li>
	<li>Post writers can quote realtime survey result in posts by chart. </li>
	<li>Flexible css design, can customize to compliant with any theme.</li>
</ul>

Visite <a href="http://xintao.3space.info/">Just another survey tool </a>to get more information.
== Installation ==
JAST can be installed via the WordPress.org plugin directory. You can download the package from here, and upload the files to your server. Or you can open <strong>Plugins -> Add new</strong> menu in Wordpress backend and search for "Just another survey tool" and install the first result item.

After activating JAST, you need add some css rules which can altered later to your theme's style.css to make the questionnaire show correct. You can do this by two ways:

<ul>
<li>Add "@import url('../../plugins/jast-another-survey-tool/public/assets/css/public.css');" (no quotation marks) to style.css, must before any other rules.</li>
<li>Copy the content in wp-content/plugins/jast-another-survey-tool/public/assets/css/public.css to style.css, and copy wp-content/plugins/jast-another-survey-tool/public/assets/css/ajax-loader.gif to your theme's directory.</li>
</ul>

Then you are able to improve your web site by supply professtional questionnaires.

== Work with JAST ==
You can get more information at <a href="http://xintao.3space.info/work-with-jast/">Work with JAST</a>.

= Design the questionnaire. =

JAST add a new post type <strong>Survey</strong> in wordpress, you can add a questionnaire by add a new <strong>Survey</strong> post. It's just like a normal post, you can write anything and use css to style it. Also you use shortcode <strong>lxt_jast_qust</strong> to define survey questions. The content between shortcode tags is the question's title, and the shotcode also have a few properties which are:
<ul>
	<li><em style="white-space:pre">name</em><label> - A key to indentify the question, must supply.</label></li>
	<li><em style="white-space:pre">type  </em><label> - Html 5 input element
	type, select and textarea element, mostly used are radio\checkbox\text\textarea. Use "text" when not supply.</label></li>
	<li><em style="white-space:pre">class </em><label> - Css class, not required. Use when you need diffrent question layout.</label></li>
	<li><em style="white-space:pre">option</em><label> - Question answer options separate by a semi-colon.</label></li>
</ul>
Below is an example <strong>Survey</strong> post content.
<pre>
[lxt_jast_qust name="1" type="checkbox" option="Business site;Personal blog;Have a try;"]<h2>1. What do you use <em>Wordpress</em> for?</h2>[/lxt_jast_qust]
[lxt_jast_qust name="2" type="radio" option="Yes;No"]2. Do you need a survey tool for Wordpress?[/lxt_jast_qust]
[lxt_jast_qust name="4" type="select" option=";Chrome;Fire fox;Opera;IE"]3. What's your favourite broswer?[/lxt_jast_qust]
[lxt_jast_qust name="3" type="textarea"]4. What is the most important function a survey tool needed?[/lxt_jast_qust]
[lxt_jast_submit value="Done>>"]
</pre>
You can copy the code to <strong>Survey</strong> post editor and edit it to see the effect.

At the end of a questionnaire you need add shotcode <strong>lxt_jast_submit</strong>, which will add a submit button the vistor can submit his answer. The example above showed how to use it. There are four property of <strong>lxt_jast_submit</strong>:
<ul>
	<li><em style="white-space:pre">value    </em><label> - Text of the button.</label></li>
	<li><em style="white-space:pre">afterurl</em><label> - Redirect url after submit the answer when show questionnaire as normal page, default is previous page.</label></li>
	<li><em style="white-space:pre">class     </em><label> - Css class, not required. Use when you need diffrent question layout.</label></li>
	<li><em style="white-space:pre">message </em><label> - Greeting text
	showed after submiting the answer, don't show greeting when not supply.</label></li>
</ul>

= Let visitors do the questionnaire. =
Visitor can view and do the questionnaire by two ways, by popup window or normal page. I recommend popup window for small questionnaire which have less than 5 questions. One can put a popup window link by widget which fixed on the site or by shortcode which showed within post or page. The plugin have a widget called <em>Survey</em>, it will contain a link which popup the survey you selected. 

Sometimes a survey relate with a post's subject, it make sense to put the survey's link within the post. One can do this by shortcode <strong>lxt_jast_survey</strong>. It has a property named <em>title</em>, whose value is the title of the survey want to popup. Below is an example of how to use it.
<pre>
[lxt_jast_survey title="Survey about WordPress"]
</pre>

For some survey more complicate and serious, vistor can view it by a normal page. <strong>Survey</strong> posts don't show on the front page by default. You need add a link to the survey page manually at where you want the link display.The link URL can be get by <em>Get shortlink</em> button at survey edit page.

= View and use the survey results =
Administrator can view the survey results at <strong>Survey results</strong> page which under the <strong>Survey</strong> menu. The operation on this page is straightforward, just select the survey and question by combo-box to see the result.

Sometimes we need show the survey results to the reader, shortcode <strong>lxt_jast_result</strong> will do that, it display a realtime result chart in post or page. For example:
<pre>
[lxt_jast_result title="Survey about WordPress" name="2"]
</pre>
This shortcode has three properties:
<ul>
	<li><em style="white-space:pre">title    </em> - title of the survey post, must not null.</li>
	<li><em style="white-space:pre">name</em> - which indicate the question, when not supply, it will show the chart of all questions which have option answer.</li>
	<li><em style="white-space:pre">type   </em> - indicate the chart type, can be <em>pie</em> and <em>bar</em>, when not supply, it will use <em>pie</em> for radio type question and <em>bar</em> for checkbox type question.</li>
</ul>
You can get more information at <a href="http://xintao.3space.info/work-with-jast/">Work with JAST</a>.
== Frequently Asked Questions ==

= Why the survey post dosn't show correct? =
This my happen when content of public.css dosn't merge to style.css of current
theme. If use @import to merge the CSS, pay attention to the single quotes which surrounding
thr url of public.css.

= How could change the permalink of Survey post type =
You can set *Survey* post type slug at *Settings -> Survey*.

= Why my *Survey* post type slug dosn't work  =
After you change the permalink structure, you need to re-active the plugin to
flush the rewrite rule.


== Screenshots ==

1. A popup survey questionnaire
2. Post quoted survey result chart
3. Show survey result in table
4. Get the url of Survey page
5. Set up the <strong>Survey</strong> widget

== Changelog ==

= 0.9.0 =
The first version.

== Upgrade Notice == 

No upgrade needed now.
