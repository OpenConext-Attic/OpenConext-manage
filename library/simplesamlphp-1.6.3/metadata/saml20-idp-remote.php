<?php
/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://rnd.feide.no/content/idp-remote-metadata-reference
 */

/*
 * DEV enginbblock IdP
 */
$metadata'https://engine.dev.surfconext.nl/authentication/idp/metadata' = array (
	'entityid' => 'https://engine.dev.surfconext.nl/authentication/idp/metadata',
	'metadata-set' => 'saml20-idp-remote',
	'expire' => 1301349600,
	'SingleSignOnService' =>
		array (
			0 =>
			array (
				'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
				'Location' => 'https://engine.dev.surfconext.nl/authentication/idp/single-sign-on',
			),
		),
	'SingleLogoutService' => array (),
	'ArtifactResolutionService' => array (),
	'certFingerprint' => array (
		0 => 'afe71c28ef740bc87425be13a2263d37971da1f9',
	),
	'certData' => 'MIICgTCCAeoCCQCbOlrWDdX7FTANBgkqhkiG9w0BAQUFADCBhDELMAkGA1UEBhMCTk8xGDAWBgNVBAgTD0FuZHJlYXMgU29sYmVyZzEMMAoGA1UEBxMDRm9vMRAwDgYDVQQKEwdVTklORVRUMRgwFgYDVQQDEw9mZWlkZS5lcmxhbmcubm8xITAfBgkqhkiG9w0BCQEWEmFuZHJlYXNAdW5pbmV0dC5ubzAeFw0wNzA2MTUxMjAxMzVaFw0wNzA4MTQxMjAxMzVaMIGEMQswCQYDVQQGEwJOTzEYMBYGA1UECBMPQW5kcmVhcyBTb2xiZXJnMQwwCgYDVQQHEwNGb28xEDAOBgNVBAoTB1VOSU5FVFQxGDAWBgNVBAMTD2ZlaWRlLmVybGFuZy5ubzEhMB8GCSqGSIb3DQEJARYSYW5kcmVhc0B1bmluZXR0Lm5vMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDivbhR7P516x/S3BqKxupQe0LONoliupiBOesCO3SHbDrl3+q9IbfnfmE04rNuMcPsIxB161TdDpIesLCn7c8aPHISKOtPlAeTZSnb8QAu7aRjZq3+PbrP5uW3TcfCGPtKTytHOge/OlJbo078dVhXQ14d1EDwXJW1rRXuUt4C8QIDAQABMA0GCSqGSIb3DQEBBQUAA4GBACDVfp86HObqY+e8BUoWQ9+VMQx1ASDohBjwOsg2WykUqRXF+dLfcUH9dWR63CtZIKFDbStNomPnQz7nbK+onygwBspVEbnHuUihZq3ZUdmumQqCw4Uvs/1Uvq3orOo/WJVhTyvLgFVK2QarQ4/67OZfHd7R+POBXhophSMv1ZOo',
);

/*
 * TEST enginbblock IdP
 */

// ToDO

/*
 * ACC enginbblock IdP
 */
$metadata['https://engine.acc.surfconext.nl/authentication/idp/metadata'] = array (
  'entityid' => 'https://engine.acc.surfconext.nl/authentication/idp/metadata',
  'metadata-set' => 'saml20-idp-remote',
  'expire' => 1301349600,
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://engine.acc.surfconext.nl/authentication/idp/single-sign-on',
    ),
  ),
  'SingleLogoutService' => 
  array (
  ),
  'ArtifactResolutionService' => 
  array (
  ),
  'certFingerprint' => 
  array (
    0 => 'afe71c28ef740bc87425be13a2263d37971da1f9',
  ),
  'certData' => 'MIICgTCCAeoCCQCbOlrWDdX7FTANBgkqhkiG9w0BAQUFADCBhDELMAkGA1UEBhMCTk8xGDAWBgNVBAgTD0FuZHJlYXMgU29sYmVyZzEMMAoGA1UEBxMDRm9vMRAwDgYDVQQKEwdVTklORVRUMRgwFgYDVQQDEw9mZWlkZS5lcmxhbmcubm8xITAfBgkqhkiG9w0BCQEWEmFuZHJlYXNAdW5pbmV0dC5ubzAeFw0wNzA2MTUxMjAxMzVaFw0wNzA4MTQxMjAxMzVaMIGEMQswCQYDVQQGEwJOTzEYMBYGA1UECBMPQW5kcmVhcyBTb2xiZXJnMQwwCgYDVQQHEwNGb28xEDAOBgNVBAoTB1VOSU5FVFQxGDAWBgNVBAMTD2ZlaWRlLmVybGFuZy5ubzEhMB8GCSqGSIb3DQEJARYSYW5kcmVhc0B1bmluZXR0Lm5vMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDivbhR7P516x/S3BqKxupQe0LONoliupiBOesCO3SHbDrl3+q9IbfnfmE04rNuMcPsIxB161TdDpIesLCn7c8aPHISKOtPlAeTZSnb8QAu7aRjZq3+PbrP5uW3TcfCGPtKTytHOge/OlJbo078dVhXQ14d1EDwXJW1rRXuUt4C8QIDAQABMA0GCSqGSIb3DQEBBQUAA4GBACDVfp86HObqY+e8BUoWQ9+VMQx1ASDohBjwOsg2WykUqRXF+dLfcUH9dWR63CtZIKFDbStNomPnQz7nbK+onygwBspVEbnHuUihZq3ZUdmumQqCw4Uvs/1Uvq3orOo/WJVhTyvLgFVK2QarQ4/67OZfHd7R+POBXhophSMv1ZOo',
);

/*
 * PROD enginbblock IdP
 */
$metadata['https://engine.surfconext.nl/authentication/idp/metadata'] = array (
  'entityid' => 'https://engine.surfconext.nl/authentication/idp/metadata',
  'metadata-set' => 'saml20-idp-remote',
  'expire' => 1301349600,
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://engine.surfconext.nl/authentication/idp/single-sign-on',
    ),
  ),
  'SingleLogoutService' => 
  array (
  ),
  'ArtifactResolutionService' => 
  array (
  ),
  'certFingerprint' => 
  array (
    0 => 'a36aac83b9a552b3dc724bfc0d7bba6283af5f8e',
  ),
  'certData' => 'MIIDyzCCArOgAwIBAgIJAMzixtXMUH1NMA0GCSqGSIb3DQEBBQUAMHwxCzAJBgNVBAYTAk5MMRAwDgYDVQQIDAdVdHJlY2h0MRAwDgYDVQQHDAdVdHJlY2h0MRUwEwYDVQQKDAxTVVJGbmV0IEIuVi4xEzARBgNVBAsMClNVUkZjb25leHQxHTAbBgNVBAMMFGVuZ2luZS5zdXJmY29uZXh0Lm5sMB4XDTExMDEyNDEwMTg1N1oXDTIxMDEyMzEwMTg1N1owfDELMAkGA1UEBhMCTkwxEDAOBgNVBAgMB1V0cmVjaHQxEDAOBgNVBAcMB1V0cmVjaHQxFTATBgNVBAoMDFNVUkZuZXQgQi5WLjETMBEGA1UECwwKU1VSRmNvbmV4dDEdMBsGA1UEAwwUZW5naW5lLnN1cmZjb25leHQubmwwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDMJ6v+f3owS3KR5IXSil+3XFwGvCVeYx3jDOFKAnwvXlDpTu+t730b8/spHtlopyJVAlb6qBIPN7R4TGTLqiu0zebYsYx/PtqCk5cbu9qs3h+p2BBoTXVwXA/ZYi0tqtxp04hcNrRj1TAgLyC0S+KASTF+zzccAcjTBid5EMioo+YllgSEobWJ4X33XVRqNrikAPDsNmDrdKUi257JSO2xhVIG5lbtmDaL5ORCD56oRmVdp7VQTEQ3Yass8J5Rn+Ub6WmRBYeG+KzFBvtyBput2o0/gvtJn9L+NWeDB0LyUPaUYG/X4GF14FcmFQfz7I5jBCNHtPcLJbPYbZKQNhz/AgMBAAGjUDBOMB0GA1UdDgQWBBS9QqP8gtMM6nm4oYzNbgqhEDP1aDAfBgNVHSMEGDAWgBS9QqP8gtMM6nm4oYzNbgqhEDP1aDAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4IBAQBH2qyYwLwesIOxUTj+NJ0VXRBDH8VecNLiUUs9Np4x8A0pxLvlNnv5TdJAruEg1LSVmAqqPUdAB2m7CKDeUVM9cwOB7vqelV2GNgOfevXi+DZRMffyyE8qyIcnTqvDOgcR8qGTPSVT+SIsOkV9bYrjltrbnal7cJermsA8SC5w/pjLaOHI1xIZHquZzymWoN3Zfz2CQg2r5o+AURYd74GrHhHqVa9VrdWtcimB+vTQQihoLt8YciehpJjOMpx2D66eFfpC8ix31RRdjAVIo1y33h1yU3gEHePDbOthZE+lpXi2WJqO85H85LqJOtgn2WPI3P2Tx32Cq1WXCYkxLaPI',
);


