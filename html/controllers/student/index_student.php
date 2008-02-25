<?php

function nav_student() 
{
  global $config_sensitive;

  $pds_url = $config_sensitive['pds']['url'];
  // This greatly depends on the existance of a PDSystem sister
  if(empty($pds_url))
  {
    return array
    (
      "home"=>array
      (
        array("home", "home", "placement_home", "placement_home"), 
      ), 
      "career"=>array
      (
        array("CV Store", "career", "cv store", "list_stored_cvs"),
      ), 
      "placement"=>array
      (
        array("Placement Home", "placement", "placement_home", "placement_home"),
        array("Companies", "placement", "company_directory", "company_directory"),
        array("Vacancies", "placement", "vacancy_directory", "vacancy_directory"),
        array("Applications", "placement", "list_applications", "list_applications"),
        array("Assessment", "placement", "list_assessments", "list_assessments"),
        array("Resources", "placement", "list_resources", "list_resources"),
        array("Notes", "placement", "manage_notes", "list_notes")
      )
    );
  }
  else
  {
    return array
    (
      "home"=>array
      (
        array("home", "home", "home", "home", "", "$pds_url?section=home&function=home"), 
        array("messages", "home", "messages", "list_messages", "$pds_url?section=home&function=list_messages"), 
        array("calendar", "home", "calendar", "view_calendar", "$pds_url?section=home&function=view_calendar"), 
        array("contacts", "home", "contacts", "list_contacts", "$pds_url?section=home&function=list_contacts"), 
        array("email", "home", "email", "open_email", "$pds_url?section=home&function=open_email"), 
        array("artefacts", "home", "artefacts", "list_artefacts", "$pds_url?section=home&function=list_artefacts")
      ), 
      "profile"=>array
      (
        array("personal details", "profile", "personal details", "view_personal_details", "$pds_url?section=profile&function=view_personal_details"),
        array("qualifications", "profile", "qualifications", "manage_qualifications", "$pds_url?section=profile&function=manage_qualifications"),
        array("work experience", "profile", "work_experiences", "manage_work_experiences", "$pds_url?section=profile&function=manage_work_experiences"),
        array("extra curricular", "profile", "extra_curriculars", "manage_extra_curriculars", "$pds_url?section=profile&function=manage_extra_curriculars"),
        array("achievements", "profile", "achievements", "manage_achievements", "$pds_url?section=profile&function=manage_achievements"),
        array("publications", "profile", "publications", "manage_publications", "$pds_url?section=profile&function=manage_publications"),
        array("conferences", "profile", "conferences", "manage_conferences", "$pds_url?section=profile&function=manage_conferences")
      ), 
      "programme"=>array
      (
        array("team", "programme", "team", "view_programme_team", "$pds_url?section=programme&function=view_programme_team"),
        array("resources", "programme", "resources", "list_resources", "$pds_url?section=programme&function=list_resources"),
        array("downloads", "programme", "downloads", "list_downloads", "$pds_url?section=programme&function=list_downloads"),
        array("meetings", "programme", "meetings", "list_meetings", "$pds_url?section=programme&function=list_meetings"),
        array("transcript", "programme", "transcript", "view_transcript", "$pds_url?section=programme&function=view_transcript")
      ), 
      "development"=>array
      (
        array("skills", "development", "skills", "view_skills", "$pds_url?section=development&function=view_skills"),
        array("goals", "development", "goals", "view_goals", "$pds_url?section=development&function=view_goals"),
        array("plans", "development", "plans", "view_plans", "$pds_url?section=development&function=view_plans"),
        array("journals", "development", "journals", "view_journals", "$pds_url?section=development&function=view_journals"),
        array("learning styles", "development", "learning styles", "view_learning_styles", "$pds_url?section=development&function=view_learning_styles")
      ), 
      "career"=>array
      (
        array("CV Builder", "career", "cv builder", "cv_builder", "$pds_url?section=career&function=cv_builder"),
        array("CV Store", "career", "cv store", "list_stored_cvs", "$pds_url?section=career&function=list_stored_cvs"),
        array("Covering Letters", "career", "covering letters", "list_covering_letters", "$pds_url?section=career&function=list_covering_letters"),
        array("Applications", "career", "application", "list_applications", "$pds_url?section=career&function=list_applications"),
        array("interviews", "career", "interviews", "list_interviews", "$pds_url?section=career&function=list_interviews"),
        array("Personal Statements", "career", "personal statements", "list_personal_statements", "$pds_url?section=career&function=list_personal_statements")
      ), 
      "placement"=>array
      (
        array("Placement Home", "placement", "placement_home", "placement_home"),
        array("Companies", "placement", "company_directory", "company_directory"),
        array("Vacancies", "placement", "vacancy_directory", "vacancy_directory"),
        array("Applications", "placement", "list_applications", "list_applications"),
        array("Assessment", "placement", "list_assessments", "list_assessments"),
        array("Resources", "placement", "list_resources", "list_resources"),
        array("Notes", "placement", "manage_notes", "list_notes")
      ), 
      "portfolios"=>array
      (
        array("portfolios", "portfolios", "portfolios", "list_portfolios", "$pds_url?section=portfolios&function=list_portfolios"),
        array("shared to others", "portfolios", "shares", "list_shared_portfolios", "$pds_url?section=portfolios&function=list_shared_portfolios"),
        array("submitted to others", "portfolios", "submissions", "list_submitted_portfolios", "$pds_url?section=portfolios&function=list_submitted_portfolios"),
        array("portfolios from others", "portfolios", "portfolios from others", "list_portfolios_from_others", "$pds_url?section=portfolios&function=list_portfolios_from_others")
      )
    );
  }
}

?>