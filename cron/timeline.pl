#!/usr/bin/perl

# OPUS
# Creates a timeline in the database
# This script was original written by Eoin Mullan, adapted by Colin Turner

use strict;
use GD;
use DBI;

################################################################################
# This script creates a timeline for one student. It should be passed the
# systems id number for the student (which is different from their student ID)
# The four constants listed in block capitals at the beginning of the script
# determine different properties of the jpeg that the script will create.
# In the createTimeline function, the variables $start_year, $start_month,
# $end_year, and $end_month should be set to imform the script the earliest and
# most recent times that should be represented on the image.
################################################################################

################################################################################
# Updated on: 23-Apr-2005
# Script can now be passed specific start and end dates by calling it like:
# perl timeline.pl <students PMS ID> <start year> <start month> <end year>
#                                                                    <end month>
# The months should be passed in as a number from 1 to 12. If times are passed
# to the script in this way they will override the default values. To use the
# default values, the script should be called with a single parameter, i.e. the
# students PMS ID.
################################################################################

# Set the constants used to draw the timeline (measurments in pixels)
use constant PIC_HEIGHT => 100;         # This should not be set lower than 100
use constant PIC_LENGTH => 600;
use constant LINE_THICKNESS => 5;       # Thickness in pixels of the timeline
use constant MONTH_TEXT_SPACING => 30;  # This constant determines the minimum
                                        # distance between two months that the
                                        # script will still print both month
                                        # names on the image. It is set to
                                        # prevent month one month name
                                        # overwriting another

#my $db_hostname = 'localhost';
#my $db_database = 'placement';
#my $db_username = 'placement';
#my $db_password = '7FNH3s6';

my $db_hostname = 'pdsdb.ws.ulster.ac.uk';
my $db_database = 'pds_pms_live';
my $db_username = 'pds';
my $db_password = 'pdsdevelop';

print $db_hostname;

exit;

# Set variables that will be passed to the program
my $user_id = $ARGV[0];

my @dates = ();

# Firstly connect to the database and get all the information on a student
# by calling the getDates subroutine
my ($student_no, $real_name, $year, $status, @dates)=getDates($user_id, @dates);

# Now a list of all the dates on which the student applied for are in the
# array @dates, and the variables $student_no, $real_name, $year and $status
# also hold useful information on the student which is needed to create the
# timeline

createTimeline($student_no, $real_name, $year, $status, @dates);

################
## Subroutine ##
################
sub createTimeline
{
  # This subroutine creates the graph and outputs it to STDOUT. This data
  # should always be redirected appropriatly or captured by the calling
  # program.

  my $student_no = shift @_;
  my $real_name = shift @_;
  my $year = shift @_;
  my $status = shift @_;
  my %dates = {};

  my $start_year = $ARGV[1] || $year - 1; # Set start year to second argument,
                                          # or if none is given, use default
  my $start_month = $ARGV[2] || '11';     # Set month to third argument, or if
                                          # none is given, use the default
  my $end_year = $ARGV[3] || $year;       # Set end year to forth atgument,
                                          # or if none is given, use default
  my $end_month = $ARGV[4] || '12';       # Set end month to fifth argument,
                                          # or if none is given, use default

  # In the case where a student applies for many jobs on the same day
  # a thinker line will be used on the timeline. The line will be one pixel
  # thick for each application made on that day. So, find out how many
  # applications were made on each day
  foreach my $date(@_)
  {
    if($dates{$date})
    {
      $dates{$date}++;
    }
    else
    {
      $dates{$date} = 1;
    }
  }

  # Now create the timeline onto which the data will be placed

  # FIrstly, variables are created and set to hold data on dimensions being
  # used. e.g. pixels per month on the timeline, and how many pixels long
  # the entire timeline will be
  # The timeline will be 9/10 of the entire width of the jpeg
  my $line_length = (9 * PIC_LENGTH) / 10;
  my $left_border = PIC_LENGTH / 20;
  my $line_height = PIC_HEIGHT / 5;
  my $top_border = PIC_HEIGHT * 2 / 5;
  my $year_diff = $end_year - $start_year;
  my $months = ($year_diff * 12) + $end_month - $start_month + 1;
  my $month_length = $line_length / ($months-1);

  # Create the image
  my $image = new GD::Image(PIC_LENGTH, PIC_HEIGHT);

  # Set the colors that may be used to create the graph
  my $white = $image->colorAllocate(255,255,255);  # N.B. the first color
  my $black = $image->colorAllocate(0,0,0);        # defined is the background
  my $green = $image->colorAllocate(0,255,127);    # color
  my $grey = $image->colorAllocate(140,140,140);
  my $red = $image->colorAllocate(255,0,0);
  my $orange = $image->colorAllocate(255,165,0);
  my $yellow = $image->colorAllocate(255,255,0);
  my $blue = $image->colorAllocate(0,0,255);
  my $dark_green = $image->colorAllocate(0,100,0);
  my $dark_blue = $image->colorAllocate(0,0,128);
  my $purple = $image->colorAllocate(160,32,240);

  # And decorate the image
  $image->transparent($white);
  # Draw a black rectangle around the image
  #$image->rectangle(1, 1, PIC_LENGTH-1, PIC_HEIGHT-1, $black);

  # Create the timeline polygon and add it
  my $poly = new GD::Polygon;
  $poly->addPt($left_border, $top_border);
  $poly->toPt(0,$line_height);
  $poly->toPt($line_length, 0);
  $poly->toPt(0,-$line_height);
  # unclosedPoylgon not working, so return to starting point
  $poly->toPt(0,$line_height);
  $poly->toPt(-$line_length, 0);
  #$poly->setThickness(LINE_THICKNESS);        # Doesn't work
  # setThickness doesn't seem to be working either so...
  my $brush = new GD::Image(LINE_THICKNESS, LINE_THICKNESS);
  my $brush_white = $brush->colorAllocate(255,255,255);   # Black
  my $brush_color;
  # If the student is placed the timeline is draws in green
  if ('Placed' eq $status)
  {
    $brush_color = $brush->colorAllocate(0,225,127);   # green
  }
  elsif ('Required' eq $status)
  {
    $brush_color = $brush->colorAllocate(0,0,0); # grey
  }
  else
  {
    $brush_color = $brush->colorAllocate(140,140,140); # black
  }
  $brush->transparent($white);
  # filledArc not working so...
  $brush->arc(LINE_THICKNESS/2, LINE_THICKNESS/2, LINE_THICKNESS, LINE_THICKNESS, 0, 360, $brush_color);
  $brush->fill(LINE_THICKNESS/2,LINE_THICKNESS/2,$brush_color);
  $image->setBrush($brush);
  $image->openPolygon($poly, gdBrushed);

  # Add in the months along the x axis
  my $last_inserted = 0 - MONTH_TEXT_SPACING;
  # This variable will be used inside the loop to
  # prevent month names being written in top of
  # each other on the timeline
  foreach(0..($months-1))
  {
    my $month_text = getMonthText(($start_month + $_)%12);

    # Not insert the month name onto the graph
    my $insert = ($left_border-10) + ($month_length * $_);
    if ($insert > ($last_inserted + MONTH_TEXT_SPACING))
    {
      # It is ok to insert the month label, as it will not clash with
      # the previous label
      $image->string(gdLargeFont,($left_border-10) + ($month_length * $_), (PIC_HEIGHT)*3/5 + 10, "$month_text", $black);
      $image->line($left_border + ($month_length * $_), PIC_HEIGHT*3/5, $left_border + ($month_length * $_),PIC_HEIGHT*3/5 +10, $black);
      $last_inserted = $insert;
    }
    else
    {
      # If the label for this month was written in it would be printed
      # over the last label to be written. So just add in a small vertical
      # line to indicate the month
      $image->line($left_border + ($month_length * $_), PIC_HEIGHT*3/5, $left_border + ($month_length * $_),PIC_HEIGHT*3/5 + 5, $black);
    }
    # If its the first or last label, or the label is for Janurary, also
    # add the year below
    if((0 == $_) || ($_ == ($months-1)) || ('Jan' eq $month_text))
    {
      use integer;
      my $year = $start_year + (($start_month + $_ - 1) / 12);
      no integer;
      $image->string(gdGiantFont,($left_border-15) + ($month_length * $_), (PIC_HEIGHT)*3/5 + 25, "$year", $black);
    }
  }

  # Now, for every time the student applied for a job, add a vertical
  # stroke to the timeline
  while( my($date, $frequency) = each %dates)
  {
    # check $date is valid data, and extract the year, month and date
    next unless ($date =~ /(\d{4})-(\d{2})-(\d{2})/);

    # Check that the date is within the range of the timeline
    # If the date is before the start of the timeline...
    if (($1 < $start_year) || (($1 == $start_year) && ($2 < $start_month)))
    {
      # Draw an arrow at the left of the timeline, pointing to the left
      my $marker = new GD::Image(3,3);
      my $orange_mark = $marker->colorAllocate(255,165,0);
      $marker->line(1,2,2,1,$orange_mark);
      $marker->line(2,3,3,2,$orange_mark);
      $image->setBrush($marker);
      $image->line($left_border, PIC_HEIGHT/2, $left_border+20, PIC_HEIGHT/2, gdBrushed);
      $image->line($left_border, PIC_HEIGHT/2, $left_border+7, PIC_HEIGHT/2-7, gdBrushed);
      $image->line($left_border, PIC_HEIGHT/2, $left_border+7, PIC_HEIGHT/2+7, gdBrushed);
      next;
    }
    # If the date is after the end of the timeline...
    if (($1 > $end_year) || (($1 == $end_year) && ($2 >= $end_month)))
    {
      # Draw an arrow at the left of the timeline, pointing to the left
      my $marker = new GD::Image(3,3);
      my $orange_mark = $marker->colorAllocate(255,165,0);
      $marker->line(1,2,2,1,$orange_mark);
      $marker->line(2,3,3,2,$orange_mark);
      $image->setBrush($marker);
      $image->line(PIC_LENGTH - $left_border, PIC_HEIGHT/2, PIC_LENGTH - $left_border-20, PIC_HEIGHT/2, gdBrushed);
      $image->line(PIC_LENGTH - $left_border, PIC_HEIGHT/2, PIC_LENGTH - $left_border-7, PIC_HEIGHT/2+7, gdBrushed);
      $image->line(PIC_LENGTH - $left_border, PIC_HEIGHT/2, PIC_LENGTH - $left_border-7, PIC_HEIGHT/2-7, gdBrushed);
      next;
    }
    # Now find how far along the timeline the mark should be made
    # In the next line $3, $2 and $1 are the year, month and day of the
    # application the student made respecively. They were set in the regular
    # expression above. 1 is subtracted from $3 because the 1st day of the
    # month is 0 days into the month
    # N.B. x_factor is the factor in the x axis
    my $x_factor = (($1 * 12) + $2 + (($3-1)/31)) - (($start_year * 12) + $start_month);
    # Set how wide the line will be, i.e. one pixel wide for each
    # each application made on a day
    my $marker = new GD::Image($frequency,1);
    my $red_mark = $marker->colorAllocate(255,0,0);
    $marker->filledRectangle(1,1,$frequency,1,$red_mark);
    $marker->setPixel(1,1,$red_mark);
    $image->setBrush($marker);
    $image->line($left_border + ($x_factor * $month_length), PIC_HEIGHT/4, $left_border + ($x_factor * $month_length), PIC_HEIGHT*3/5, gdBrushed);
  }

  # Add name and student no along the top of the image (number written first
  # because it has a more predictable length
  $image->string(gdLargeFont,$left_border, 5, "Student no:", $black);
  $image->string(gdLargeFont,$left_border+95, 5, "$student_no", $dark_green);
  $image->string(gdLargeFont,$left_border+180, 5, "Student Name:", $black);
  $image->string(gdLargeFont,$left_border+295, 5, "$real_name", $dark_green);
  $image->string(gdLargeFont,PIC_LENGTH - $left_border - 12, 5, "$#_",
                                                                  $dark_green);
  # "$#_" give the number of the last element of the array, in this case
  # that is also the number of applications made as the array contain all
  # the dates and one more piece of data

  # Finally output the graph
  binmode STDOUT;
  print $image->jpeg;

  sub getMonthText
  {
    ($_[0] eq '1') && (return ('Jan'));
    ($_[0] eq '2') && (return ('Feb'));
    ($_[0] eq '3') && (return ('Mar'));
    ($_[0] eq '4') && (return ('Apr'));
    ($_[0] eq '5') && (return ('May'));
    ($_[0] eq '6') && (return ('Jun'));
    ($_[0] eq '7') && (return ('Jul'));
    ($_[0] eq '8') && (return ('Aug'));
    ($_[0] eq '9') && (return ('Sep'));
    ($_[0] eq '10')&& (return ('Oct'));
    ($_[0] eq '11')&& (return ('Nov'));
    ($_[0] eq '0') && (return ('Dec'));
  }
}

# This subroutine takes in a student number and returns all the dates on which
# that sutdent made an application. If no applications have been made by the
# student a blank string will be returned
sub getDates
{
  my $user_id = shift @_;
  my @dates = @_;
  $dates[0] = \0;

  # Get the information needed to connect to the database
  #`grep "\$conf\\['database'\\]\\['username'\\]" ../include/config.php` =~ /= '(\w+)';$/;
  my $user = $1;
  #`grep "\$conf\\['database'\\]\\['database'\\]" ../include/config.php` =~ /= '(\w+)';$/;
  my $database = $1;
  #`grep "\$conf\\['database'\\]\\['password'\\]" ../include/config.php` =~ /= '(\w+)';$/;
  my $password = $1;

  # For testing, the above lines are not used. Instead the program connects to
  # a test database with the information below

  # And connect to the database
  my $dbh = DBI->connect("DBI:mysql:host=$db_hostname;database=$db_database",
                          "$db_username","$db_password", {PrintError=>0, RaiseError=>1});
  # From the systems id number, get the student number
  my $sth = $dbh->prepare('SELECT student_id ' .
                          'FROM cv_pdetails ' .
                        "WHERE id = '$user_id'");
  $sth->execute();
  my $student_no = '';
  $sth->bind_columns(\$student_no);
  unless ($sth->fetch)
  {
      die "$0 could not determine the student number for id $user_id\n";
  }
  $sth->finish;

  # Got the student number, find out the times they have made an application
  $sth = $dbh->prepare('SELECT created ' .
                        'FROM companystudent ' .
                "WHERE student_id = '$user_id'");
  $sth->execute();
  my $date = '';
  $sth->bind_columns(\$date);
  while($sth->fetch)
  {
      $date =~ /\d{4}-\d{2}-\d{2}/;
      push @dates, $&;
  }
  $sth->finish;

  # Before returning the dates, find the students name, year and status
  # First the name...
  $sth = $dbh->prepare('SELECT real_name ' .
                        'FROM id ' .
		  "WHERE id_number = '$user_id'");
  $sth->execute();
  my $real_name = '';
  $sth->bind_columns(\$real_name);
  $real_name = "Couldn't find name" unless ($sth->fetch);
  $sth->finish;

  # Find the students status and year
  $sth = $dbh->prepare('SELECT status, year ' .
                        'FROM students ' .
	                "WHERE user_id = $user_id");
  $sth->execute();
  my ($status, $year) = $sth->fetchrow();
  $sth->finish;

  # Prepend this data onto the dates array;
  unshift @dates, $status;
  unshift @dates, $year;
  unshift @dates, $real_name;
  unshift @dates, $student_no;

  # Disconnect from the database
  $dbh->disconnect;

  return(@dates);
}
