<?php

function nav_student() 
{
  global $config_sensitive;

  $pds_url = $config_sensitive['pds']['real_url'];
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
        array("CV Store", "career", "cv store", "manage_cvs"),
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
      "myPDS"=>array
      (
        array("home", "home", "", "", "", "$pds_url?section=home&function=home"), 
        array("messages", "home", "", "", "", "$pds_url?section=home&function=list_messages"), 
        array("calendar", "home", "", "", "", "$pds_url?section=home&function=view_calendar"), 
        array("contacts", "home", "", "", "", "$pds_url?section=home&function=list_contacts"), 
        //array("email", "home", "", "", "", "$pds_url?section=home&function=open_email"), 
        array("artefact files", "home", "", "", "", "$pds_url?section=home&function=manage_artefact_files"),
        array("artefact urls", "home", "", "", "", "$pds_url?section=home&function=manage_artefact_urls")
      ), 
      "myProfile"=>array
      (
        array("personal details", "profile", "", "", "", "$pds_url?section=profile&function=view_personal_details"),
        array("qualifications", "profile", "", "", "", "$pds_url?section=profile&function=manage_qualifications"),
        array("work experience", "profile", "", "", "", "$pds_url?section=profile&function=manage_work_experiences"),
        array("extra curricular", "profile", "", "", "", "$pds_url?section=profile&function=manage_extra_curriculars"),
        array("achievements", "profile", "", "", "", "$pds_url?section=profile&function=manage_achievements"),
        array("publications", "profile", "", "", "", "$pds_url?section=profile&function=manage_publications"),
        array("conferences", "profile", "", "", "", "$pds_url?section=profile&function=manage_conferences")
      ), 
      "myProgramme"=>array
      (
        array("team", "programme", "", "", "", "$pds_url?section=programme&function=view_programme_team"),
        array("resources", "programme", "", "", "", "$pds_url?section=programme&function=list_resources"),
        array("downloads", "programme", "", "", "", "$pds_url?section=programme&function=list_downloads"),
        array("meetings", "programme", "", "", "", "$pds_url?section=programme&function=list_meetings"),
        array("transcript", "programme", "", "", "", "$pds_url?section=programme&function=view_transcript")
      ), 
      "myDevelopment"=>array
      (
        array("skills", "development", "", "", "", "$pds_url?section=development&function=view_skills"),
        array("goals", "development","", "", "", "$pds_url?section=development&function=view_goals"),
        array("plans", "development","", "", "", "$pds_url?section=development&function=view_plans"),
        array("journals", "development", "", "", "",  "$pds_url?section=development&function=view_journals"),
        array("learning styles", "development", "", "", "",  "$pds_url?section=development&function=view_learning_styles")
      ), 
      "myCareer"=>array
      (
        array("CV Builder", "career", "", "", "", "$pds_url?section=career&function=cv_builder"),
        array("CV Store", "career", "", "", "",  "$pds_url?section=career&function=list_stored_cvs"),
        array("Covering Letters", "career", "", "", "",  "$pds_url?section=career&function=list_covering_letters"),
        array("Applications", "career", "", "", "",  "$pds_url?section=career&function=list_applications"),
        array("interviews", "career", "", "", "",  "$pds_url?section=career&function=list_interviews"),
        array("Personal Statements", "career", "", "", "",  "$pds_url?section=career&function=list_personal_statements")
      ), 
      "myPlacement"=>array
      (
        array("Placement Home", "placement", "placement_home", "placement_home"),
        array("Companies", "placement", "company_directory", "company_directory"),
        array("Vacancies", "placement", "vacancy_directory", "vacancy_directory"),
        array("Applications", "placement", "list_applications", "list_applications"),
        array("Assessment", "placement", "list_assessments", "list_assessments"),
        array("Resources", "placement", "list_resources", "list_resources"),
        array("Notes", "placement", "manage_notes", "list_notes")
      ), 
      "myPortfolios"=>array
      (
        array("portfolios", "portfolios", "", "", "",  "$pds_url?section=portfolios&function=manage_portfolios"),
        array("shared to others", "portfolios", "", "", "",  "$pds_url?section=portfolios&function=manage_shares"),
        array("submitted to others", "portfolios", "", "", "",  "$pds_url?section=portfolios&function=manage_submissions"),
        array("portfolios from others", "portfolios", "", "", "",  "$pds_url?section=portfolios&function=shared_portfolios"),
        array("submissions from others", "portfolios", "", "", "", "$pds_url?section=portfolios&function=submitted_portfolios")
      )
    );
  }
}

?>