
-- ______________________________________________________________________________________
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- HTTP-Codes

-- 200  OK
-- 400 Bad Request (malformed request syntax, invalid request message parameters, ...)
-- 401 Unauthorized (tried to operate on a protected resource without providing the proper authorization)
-- 403 Forbidden (user does not have the necessary permissions)
-- 404 Not Found
-- 422 Unprocessable Entity (Invalid Props)
-- 505 Internal Server Error


-- ______________________________________________________________________________________
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- API Error-Codes

REPLACE INTO `sys_error_codes` (`code`, `http_code`, `concerns`, `description`, `notes`) VALUES 

('G0001',   '500',  'General',      'General/Unknown Error', ''),
('G0002',   '400',  'General',      'Invalid Json in request body', ''),

('A1002',   '404',  'Account',      'Account not found', 'Table: "account"'),
('A1021',   '404',  'Account',      'Mail not found', 'Table: "account"'),
('A1022',   '404',  'Account',      'Username not found', 'Table: "account"'),
('A1003',   '404',  'Account',      'Details not found', 'Table: "acc_detail"'),
('A1004',   '404',  'Account',      'Verification not found', 'Table: "acc_verification"'),
('A1005',   '400',  'Account',      'Mail already in use', NULL),
('A1006',   '400',  'Account',      'Username already in use', NULL),

('O1103',   '403',  'Auth/Session', 'Password incorrect', NULL),
('O1171',   '403',  'Auth/Session', 'Token expired', NULL),
('O1172',   '403',  'Auth/Session', 'Token invalid signature', NULL),
('O1173',   '500',  'Auth/Session', 'Token decode failed', NULL),
('O1187',   '403',  'Auth/Session', 'Auth-Token missing', NULL),
('O1188',   '403',  'Auth/Session', 'Session not found (DeepCheck)', NULL),
('O1189',   '403',  'Auth/Session', 'Session issuer mismatch (DeepCheck)', NULL),
('O1104',   '403',  'Auth/Session', 'Account locked', NULL),
('O1105',   '403',  'Auth/Session', 'Account deleted', NULL),
('O1131',   '403',  'Auth/Session', 'Phrase incorrect (Refresh)', NULL),
('O1132',   '403',  'Auth/Session', 'Session/Token issuer mismatch (Refresh)', NULL),
('O1133',   '403',  'Auth/Session', 'Keep disabled (Refresh)', NULL),
('O1141',   '403',  'Auth/Session', 'Permissions denied (insufficient)', NULL),
('O1142',   '403',  'Auth/Session', 'Permissions denied 2 (insufficient)', NULL),
('O1106',   '404',  'Auth/Session', 'Account-Auth not found', 'Table: "authentication"'),
('O1109',   '404',  'Auth/Session', 'Session (RefreshID) not found', 'Table: "auth_session"'),
('O1144',   '403',  'Auth/Session', 'Password-Reset Code invalid', NULL),
('O1145',   '404',  'Auth/Session', 'Password-Reset entry not found', 'Table: "auth_passreset"'),

('D0201',   '500',  'Database',     'DB Connect Error', NULL),
('D0202',   '500',  'Database',     'DB Prepare Error', NULL),
('D0203',   '500',  'Database',     'DB Bind Error', NULL),
('D0204',   '500',  'Database',     'DB Execute Error', NULL),
('D0205',   '500',  'Database',     'Unable to get insert ID', NULL),

('R0101',   '404',  'Router',       'Version mismatch', NULL),
('R0102',   '404',  'Router',       'Route not found', NULL),

('V0460',   '422',  'Validation',   'Date required', NULL),
('V0461',   '422',  'Validation',   'Date invalid', NULL),
('V0462',   '422',  'Validation',   'Date invalid (check failed)', NULL),
('V0463',   '422',  'Validation',   'Date too early', NULL),
('V0464',   '422',  'Validation',   'Date too late', NULL),
('V0430',   '422',  'Validation',   'Mail required', NULL),
('V0431',   '422',  'Validation',   'Mail length incorrect', NULL),
('V0433',   '422',  'Validation',   'Mail format invalid', NULL),
('V0410',   '422',  'Validation',   'Number required', NULL),
('V0411',   '422',  'Validation',   'Number invalid (not numeric)', NULL),
('V0412',   '422',  'Validation',   'Number length/size incorrect', NULL),
('V0440',   '422',  'Validation',   'Password required', NULL),
('V0441',   '422',  'Validation',   'Password length/size incorrect', NULL),
('V0442',   '422',  'Validation',   'Password requires uppercase', NULL),
('V0443',   '422',  'Validation',   'Password requires lowercase', NULL),
('V0444',   '422',  'Validation',   'Password requires number', NULL),
('V0445',   '422',  'Validation',   'Password requires special character', NULL),
('V0470',   '422',  'Validation',   'Time required', NULL),
('V0471',   '422',  'Validation',   'Time invalid', NULL),
('V0472',   '422',  'Validation',   'Time too early', NULL),
('V0473',   '422',  'Validation',   'Time too late', NULL),
('V0480',   '422',  'Validation',   'Username required', NULL),
('V0481',   '422',  'Validation',   'Username invalid (spaces)', NULL),
('V0482',   '422',  'Validation',   'Username invalid (should not be an email)', NULL),
('V0483',   '422',  'Validation',   'Username length/size incorrect', NULL),

('M0901',   '500',  'Mail',         'Mail send error', NULL),

('F0100',   '400',  'Files',        'Upload file missing', NULL),
('F0101',   '500',  'Files',        'Image could not be initialized', NULL),
('F0102',   '500',  'Files',        'Image upload failed', NULL),
('F0103',   '404',  'Files',        'File not found', NULL),
('F0104',   '500',  'Files',        'Unable to create directory', NULL),

('X0001',   '422',  'Scripts',      'Avatar (ID) required', 'Script: Account->Edit->Update'),

('E0001',   '404',  'Errors',       'Entry xy not found', 'Should contain class->method description.'),

('xyyzz',   '000',  'Template',     'abc', NULL);