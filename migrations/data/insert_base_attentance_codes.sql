-- Based on https://assets.publishing.service.gov.uk/government/uploads/system/uploads/attachment_data/file/818204/School_attendance_July_2019.pdf
--   plus some additions.
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('0', '[Not yet recorded]', '--', true);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('1', 'Present in school', 'P', true);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('L', 'Late arrival before the register has closed', 'P', true);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('U', 'Late - arrived in school after registration closed', 'UA', true);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('B', 'Off-site educational activity', 'P', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('D', 'Dual Registered - at another educational establishment', 'P', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('J', 'At an interview with prospective employers, or another educational establishment', 'P', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('P', 'Participating in an approved sporting activity', 'P', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('C', 'Other circumstance authorised by the school', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('E', 'Excluded but no alternative provision made', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('H', 'Holiday authorised by the school', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('F', 'Extended family holiday - approved', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('I', 'Illness (not medical or dental appointments)', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('M', 'Medical or dental appointments', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('K', 'Missions trip', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('R', 'Religious observance', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('S', 'Study leave', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('T', 'Gypsy, Roma and Traveller absence', 'AA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('G', 'Holiday not authorised by the school', 'UA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('N', 'No reason for absence provided', 'UA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('O', 'Absent from school without authorisation', 'UA', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('V', 'Educational visit or trip', 'P', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('W', 'Work experience', 'P', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('X', 'Not required to be in school', '--', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('Y', 'Unable to attend due to exceptional circumstances', '--', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('Z', 'Pupil not on admission register', '--', false);
insert into attendance_code (code, description, statistical_meaning, is_on_premises) values ('#', 'Planned whole or partial school closure', '--', false);