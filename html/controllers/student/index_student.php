<?php

function nav_student() 
{
  global $config;
  // This greatly depends on the existance of a PDSystem sister
  if(empty($config['opus']['pds']['url']))
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
        array("Companies & Vacancies", "placement", "list_vacancies", "list_vacancies"),
        array("Applications", "placement", "list_applications", "list_applications"),
        array("Assessment", "placement", "list_assessments", "list_assessments"),
        array("Resources", "placement", "list_resources", "list_resources"),
        array("Notes", "placement", "manage_notes", "manage_notes")
      ) 
    );
  }
  else
  {
    return array
    (
      "home"=>array
      (
        array("home", "home", "home", "home"), 
        array("messages", "home", "messages", "list_messages"), 
        array("calendar", "home", "calendar", "view_calendar"), 
        array("contacts", "home", "contacts", "list_contacts"), 
        array("email", "home", "email", "open_email"), 
        array("artefacts", "home", "artefacts", "list_artefacts")
      ), 
      "profile"=>array
      (
        array("personal details", "profile", "personal details", "view_personal_details"),
        array("qualifications", "profile", "qualifications", "manage_qualifications"),
        array("work experience", "profile", "work_experiences", "manage_work_experiences"),
        array("extra curricular", "profile", "extra_curriculars", "manage_extra_curriculars"),
        array("achievements", "profile", "achievements", "manage_achievements"),
        array("publications", "profile", "publications", "manage_publications"),
        array("conferences", "profile", "conferences", "manage_conferences")
      ), 
      "programme"=>array
      (
        array("team", "programme", "team", "view_programme_team"),
        array("resources", "programme", "resources", "list_resources"),
        array("downloads", "programme", "downloads", "list_downloads"),
        array("meetings", "programme", "meetings", "list_meetings"),
        array("transcript", "programme", "transcript", "view_transcript")
      ), 
      "development"=>array
      (
        array("skills", "development", "skills", "view_skills"),
        array("goals", "development", "goals", "view_skills"),
        array("plans", "development", "plans", "view_skills"),
        array("journals", "development", "journals", "view_skills"),
        array("learning styles", "development", "learning styles", "view_skills")
      ), 
      "career"=>array
      (
        array("CV Builder", "career", "cv builder", "cv_builder"),
        array("CV Store", "career", "cv store", "list_stored_cvs"),
        array("Covering Letters", "career", "covering letters", "list_covering_letters"),
        array("Applications", "career", "application", "list_applications"),
        array("interviews", "career", "interviews", "list_interviews"),
        array("Personal Statements", "career", "personal statements", "list_personal_statements")
        
      ), 
      "placement"=>array
      (
        array("Placement Home", "placement", "placement_home", "placement_home"),
        array("Companies & Vacancies", "placement", "list_vacancies", "list_vacancies"),
        array("Applications", "placement", "list_applications", "list_applications"),
        array("Assessment", "placement", "list_assessments", "list_assessments"),
        array("Resources", "placement", "list_resources", "list_resources"),
        array("Notes", "placement", "manage_notes", "manage_notes")
      ), 
      "portfolios"=>array
      (
        array("portfolios", "portfolios", "portfolios", "list_portfolios"),
        array("shared to others", "portfolios", "shares", "list_shared_portfolios"),
        array("submitted to others", "portfolios", "submissions", "list_submitted_portfolios"),
        array("portfolios from others", "portfolios", "portfolios from others", "list_portfolios_from_others")
      )
    );
  }
}

?>