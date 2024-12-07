<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticsearchService\ElasticsearchService;

class SetupElasticsearch extends Command
{
    protected $signature = 'elasticsearch:setup';
    protected $description = 'Set up Elasticsearch indices';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(ElasticsearchService $elasticsearch)
    {
        $client = $elasticsearch->getClient();

        if ($client->indices()->exists([Constants::INDEX => Constants::EMAILS])) {
            $client->indices()->delete([Constants::INDEX => Constants::EMAILS]);
            $this->info('Index "emails" deleted successfully.');
        }

        $emailMapping = [
            Constants::MAPPINGS => [
                Constants::PROPERTIES => [
                    Constants::USER_ID => [Constants::TYPE => Constants::KEYWORD],
                    Constants::EMAIL_ADDRESS => [Constants::TYPE => Constants::KEYWORD],
                    Constants::SUBJECT => [Constants::TYPE => Constants::TEXT],
                    Constants::BODY => [Constants::TYPE => Constants::TEXT],
                    Constants::SENDER => [Constants::TYPE => Constants::KEYWORD],
                    Constants::RECIPIENTS_CC => [Constants::TYPE => Constants::KEYWORD],
                    Constants::TIMESTAMP => [Constants::TYPE => Constants::DATE],
                    Constants::FOLDER => [Constants::TYPE => Constants::KEYWORD], // (e.g., Inbox, Sent)
                    Constants::IS_READ => [Constants::TYPE => Constants::BOOLEAN],
                ],
            ],
        ];
        $elasticsearch->createIndex(Constants::EMAILS, $emailMapping);

        if ($client->indices()->exists([Constants::INDEX => Constants::MAILBOXES])) {
            $client->indices()->delete([Constants::INDEX => Constants::MAILBOXES]);
            $this->info('Index "mailboxes" deleted successfully.');
        }

        $mailboxMapping = [
            Constants::MAPPINGS => [
                Constants::PROPERTIES => [
                    Constants::EMAIL_ADDRESS => [Constants::TYPE => Constants::KEYWORD],
                    Constants::FOLDERS => [
                        Constants::TYPE => Constants::NESTED,
                        Constants::PROPERTIES => [
                            Constants::NAME => [Constants::TYPE => Constants::KEYWORD],
                            Constants::MESSAGE_COUNT => [Constants::TYPE => Constants::INTEGER],
                            Constants::UNREAD_COUNT => [Constants::TYPE => Constants::INTEGER],
                        ]
                    ],
                    Constants::LAST_SYNC => [Constants::TYPE => Constants::DATE],
                ],
            ],
        ];
        $elasticsearch->createIndex(Constants::MAILBOXES, $mailboxMapping);

        $this->info('Elasticsearch indices set up successfully.');
    }
}
