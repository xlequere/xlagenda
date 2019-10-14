<?php
echo "<h3>".$lang['admin_title_date_heure']."</h3>
	<p>
	<label for=\"date_debut\">".$lang['admin_label_date_debut']."*</label><br />
	<input type=\"text\" name=\"date_debut\" id=\"date_debut\" value=\"$date_debut\" class=\"date-pick\" />
	</p>
	<hr style=\"visibility:hidden;clear:both;\" />
	<p><label for=\"heure_debut\">".$lang['admin_label_heure_debut']."</label><br />
	<input type=\"text\" name=\"heure_debut\" value=\"$heure_debut\" id=\"heure_debut\" />
	</p>
	<p>
	<label for=\"date_fin\">".$lang['admin_label_date_fin']."</label>
	<br />
	<input type=\"text\" name=\"date_fin\" id=\"date_fin\" value=\"$date_fin\" class=\"date-pick\" />
	</p>
	<hr style=\"visibility:hidden;clear:both;\" />
	<p>
	<label for=\"heure_fin\">".$lang['admin_label_heure_fin']."</label><br />
	<input type=\"text\" name=\"heure_fin\" value=\"$heure_fin\" id=\"heure_fin\" />
	</p>  
	<h3>".$lang['admin_title_infos_evenement']."</h3>
	<p>
	<label for=\"nom\">".$lang['admin_label_nom_evenement']."*</label><br />
	<input name=\"nom\" type=\"text\" id=\"nom\" value=\"".input($nom)."\" size=\"80\" />
	</p>
	<p>
	<label for=\"description\">".$lang['admin_label_description']."*</label>
	<br />
	<textarea name=\"description\" cols=\"80\" rows=\"5\" id=\"description\">".stripslashes($description)."</textarea>
	</p>
	<p>
	<label for=\"categorie\">".$lang['admin_label_categorie']."*</label><br />
	<select name=\"categorie\" id=\"categorie\">
	<option value=\"0\">-- ".$lang['admin_label_selectionner']."--</option>\n";
	//CONSTRUCTION DU MENU CATEGORIES
	foreach (getCategories() as $categorie_id => $categorie_name)
	{
		echo "<option value=\"$categorie_id\"";
		if ($categorie == $categorie_id)
		{
			echo " selected=\"selected\"";
		}
		echo ">$categorie_name</option>\n";
	}
	echo "
	</select>
	</p>
	<p>
	<label for=\"lieu\">".$lang['admin_label_lieu']."</label><br />
	<input name=\"lieu\" type=\"text\" id=\"lieu\" value=\"".input($lieu)."\" size=\"80\" />
	</p>
	<h3>".$lang['admin_title_coordonnees_contact']."</h3>
	<p>
	<label for=\"contact\">".$lang['admin_label_contact']."</label><br />
	<input name=\"contact\" type=\"text\" id=\"contact\" value=\"".input($contact)."\" size=\"80\" />
	</p>
	<p>
	<label for=\"adresse\">".$lang['admin_label_adresse']."</label><br />
	<input name=\"adresse\" type=\"text\" id=\"adresse\" value=\"".input($adresse)."\" size=\"80\" />
	</p>
	<p>
	<label for=\"email\">".$lang['admin_label_email']."</label><br />
	<input name=\"email\" type=\"text\" id=\"email\" value=\"".input($email)."\" size=\"80\" />
	</p>
	<p><label for=\"telephone\">".$lang['admin_label_tel']."</label><br />
	<input name=\"telephone\" type=\"text\" id=\"telephone\" value=\"".input($telephone)."\" size=\"20\" />
	</p>
	<p>
	<label for=\"fax\">".$lang['admin_label_fax']."</label><br />
	<input name=\"fax\" type=\"text\" id=\"fax\" value=\"".input($fax)."\" size=\"20\" />
	</p>
	<h3>".$lang['admin_title_lien']."</h3>
	<p>
	<label for=\"lien\">".$lang['admin_label_lien']."</label><br />
	<input name=\"lien\" type=\"text\" id=\"lien\" value=\"".input($lien)."\" size=\"80\" />
	</p>
	<p>
	<label for=\"url\">".$lang['admin_label_url']."</label><br />
	<input name=\"url\" type=\"text\" id=\"url\" value=\"$url\" size=\"80\" />
	</p>\n";
	if (empty($hide_status_selector))
	{
		echo "<h3>".$lang['admin_title_activer_desactiver']."</h3>
		<p>
		<input type=\"radio\" name=\"actif\" id=\"activer\" value=\"1\"";
		if ($actif)
		{
			echo "checked=\"checked\"";
		}
		echo "/>
		<label for=\"activer\">".$lang['admin_label_activer_evenement']."</label><br />
		<input type=\"radio\" name=\"actif\" id=\"desactiver\" value=\"0\"";
		if (!$actif)
		{
			echo "checked=\"checked\"";
		}
		echo "/>
		<label for=\"desactiver\">".$lang['admin_label_desactiver_evenement']."</label>
		</p>\n";
	}
?>
