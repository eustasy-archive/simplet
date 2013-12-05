<?php

	$TextTitle = 'Example Page';
	$WebTitle = 'Example Page';
	$Canonical = 'page';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'exaple page default styles';

	require '../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../header.php'; ?>

		<h2>Example Page</h2>
		<p>Simplet is a small, simple PHP Framework for building file-based that includes a responsive, HTML5 website layout with 12 fluid width columns and an integrated database-managed user system.</p>
		<p>This page will present some of the default styles to show </p>

		<hr>

		<div class="section group">
			<div class="col span_1_of_2">
				<h3>Easy Peasy</h3>
				<p>See how easy creating this page was. It's just HTML, which gives you so much power and control over everything you see.</p>
				<p><a href="http://eustasy.org">Links</a> are just as easy as they are anywhere else.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Quotes</h3>
				<blockquote>
					<p>In the beginning the Universe was created. This has made a lot of people very angry and has been widely regarded as a bad move.</p>
				</blockquote>
				<caption>Douglas Adams, The Hitchhiker's Guide to the Galaxy</caption>
			</div>
		</div>

		<hr>

		<div class="section group">
			<div class="col span_1_of_3">
				<h1>Header 1</h1>
				<h2>Header 2</h2>
				<h3>Header 3</h3>
				<h4>Header 4</h4>
				<h5>Header 5</h5>
				<h6>Header 6</h6>
			</div>
			<div class="col span_2_of_3">
				<h3>Lists</h3>
				<div class="section group">
					<div class="col span_1_of_2">
						<h4>Reasons Simplet is Cool</h4>
						<ul>
							<li>Responsive Layout</li>
							<li>Nginx Compatible</li>
							<li>User Management</li>
							<ul>
								<li>Admin Option</li>
								<li>Working Password Resets</li>
							</ul>
							<li>Open Source</li>
							<li>Quotes Douglas Adams</li>
						</ul>
					</div>
					<div class="col span_1_of_2">
						<h4>To Do</h4>
						<ol>
							<li>Add Captcha Option</li>
							<li>Add Comments for Blogs or Pages</li>
							<li>Add Comments for Blogs</li>
						</ol>

						<dl>
							<dt>Definition List Title</dt>
							<dd>This is a definition list division.</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>

		<hr>

		<h3>Code Snippets</h3>
		<code>( Lines of Code ) / 42 = ( Total Number of Bugs )</code>

		<hr>

		<h3>Text</h3>

		<p>The purpose of this HTML is to help determine what default settings are with CSS and to make sure that all possible HTML Elements are included in this HTML so as to not miss any possible Elements when designing a site.</p>
		<p class="textleft">This text is aligned to the left.</p>
		<p class="textright">This text is aligned to the right.</p>
		<p class="textcenter">This text is centered horizontally.</p>

		<hr>

		<h3>Images</h3>

		<img class="floatright" style="max-width:33%;-webkit-border-radius:50%;border-radius:50%;" src="http://lewisgoddard.eustasy.org/images/faces/circular-blue-small-cropped-compressed.png" alt="CSS &vert; God's Language" />
		<p>Lorem ipsum dolor sit amet, <a href="#" title="test link">test link</a> adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p><p>Lorem ipsum dolor sit amet, <em>emphasis</em> consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>

		<img class="floatleft" style="max-width:33%;" src="http://lewisgoddard.eustasy.org/images/faces/circular-red-small-compressed.png" alt="CSS &vert; God's Language" />
		<p>Lorem ipsum dolor sit amet, <a href="#" title="test link">test link</a> adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p><p>Lorem ipsum dolor sit amet, <em>emphasis</em> consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>

		<hr>

		<h3>Fieldsets, Legends, and Form Elements</h3>
		<fieldset>
			<legend>Legend</legend>
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus.</p>
		</fieldset>
		<form>
			<h3>Form Elements</h3>
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui.</p>
			<div class="section group">
				<div class="col span_1_of_4">
					<h3><label for="text_field">Text Field</label></h3>
				</div>
				<div class="col span_3_of_4">
					<input type="text" name="text_field" id="text_field" />
				</div>
			</div>
			<div class="section group">
				<div class="col span_1_of_4">
					<h3><label for="password">Password:</label></h3>
				</div>
				<div class="col span_3_of_4">
					<input type="password" class="password" name="password" id="password" />
				</div>
			</div>
			<div class="section group">
				<div class="col span_3_of_4"><br></div>
				<div class="col span_1_of_4">
					<input class="button" type="submit" value="Login" />
				</div>
			</div>
			<h3><label for="text_area">Text Area</label></h3>
			<textarea id="text_area"></textarea>
			<div class="section group">
				<div class="col span_1_of_4">
					<h4><label for="select_element">Select Element</label></h4>
					<p>
						<select name="select_element" id="select_element">
							<optgroup label="Option Group 1">
								<option value="1">Option 1</option>
								<option value="2">Option 2</option>
								<option value="3">Option 3</option>
							</optgroup>
							<optgroup label="Option Group 2">
								<option value="1">Option 1</option>
								<option value="2">Option 2</option>
								<option value="3">Option 3</option>
							</optgroup>
						</select>
					</p>
				</div>
				<div class="col span_1_of_4">
					<h4><label for="file">File Input</label></h4>
					<p><input type="file" class="file" name="file" id="file" /></p>
				</div>
				<div class="col span_1_of_4">
					<h4><label for="radio_button">Radio Buttons</label></h4>
					<p><input type="radio" class="radio" name="radio_button" id="radio_button" value="radio_1" /> Radio 1<br>
					<input type="radio" class="radio" name="radio_button" value="radio_2" /> Radio 2<br>
					<input type="radio" class="radio" name="radio_button" value="radio_3" /> Radio 3</p>
				</div>
				<div class="col span_1_of_4">
					<h4><label for="checkboxes">Checkboxes</label></h4>
					<p><input type="checkbox" id="checkboxes" class="checkbox" name="checkboxes" value="check_1" /> Radio 1<br>
					<input type="checkbox" class="checkbox" name="checkboxes" value="check_2" /> Radio 2<br>
					<input type="checkbox" class="checkbox" name="checkboxes" value="check_3" /> Radio 3</p>
				</div>
			</div>
			<div class="section group">
				<div class="col span_3_of_4"><br></div>
				<div class="col span_1_of_4">
					<input class="button" type="reset" value="Clear" />
				</div>
			</div>
			<div class="section group">
				<div class="col span_3_of_4"><br></div>
				<div class="col span_1_of_4">
					<input class="button" type="submit" value="Submit" />
				</div>
			</div>
		</form>

		<hr>

		<h3 id="tables">Tables</h3>
		<table>
			<tr>
				<th>Table Header 1</th>
				<th>Table Header 2</th>
				<th>Table Header 3</th>
			</tr>
			<tr>
				<td>Division 1</td>
				<td>Division 2</td>
				<td>Division 3</td>
			</tr>
			<tr class="even">
				<td>Division 1</td>
				<td>Division 2</td>
				<td>Division 3</td>
			</tr>
			<tr>
				<td>Division 1</td>
				<td>Division 2</td>
				<td>Division 3</td>
			</tr>
		</table>

		<hr>

		<h3>Misc Stuff - abbr, acronym, pre, code, sub, sup, etc.</h3>
		<p>Lorem <sup>superscript</sup> dolor <sub>subscript</sub> amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. <cite>cite</cite>. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. <abbr title="National Basketball Association">NBA</abbr> Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus. <abbr title="Avenue">AVE</abbr></p>
		<pre>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. <abbr title="National Basketball Association">NBA</abbr> Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus. <abbr title="Avenue">AVE</abbr></pre>

<?php require '../footer.php'; } ?>
