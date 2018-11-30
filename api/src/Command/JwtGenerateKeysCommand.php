<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class JwtGenerateKeysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jwt:generate-keys')
            ->setDescription('Generate ssl keys for lexikJWTBundle.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();

        $varDir = __DIR__ . '/../../var';

        $output->writeln('<info>Check keys exists.</info>');
        if (!$fs->exists([
            "$varDir/jwt/private.pem",
            "$varDir/jwt/public.pem",
        ])) {
            $output->writeln('<info>Begin generating.</info>');

            $fs->mkdir("$varDir/jwt");

            try {
                $process = new Process('openssl rand -base64 32');
                $process->mustRun();
                $passPhrase = trim($process->getOutput());
            } catch (ProcessFailedException $exception) {
                $output->writeln('<error>' . $exception->getMessage() . '</error>');

                return;
            }

            try {
                $process = new Process(
                    "openssl genrsa -out $varDir/jwt/private.pem -passout pass:$passPhrase -aes256 4096"
                );
                $process->mustRun();
            } catch (ProcessFailedException $exception) {
                $output->writeln('<error>' . $exception->getMessage() . '</error>');

                return;
            }

            try {
                $process = new Process(
                    "openssl rsa -in $varDir/jwt/private.pem -passin pass:$passPhrase -pubout -out $varDir/jwt/public.pem"
                );
                $process->mustRun();
            } catch (ProcessFailedException $exception) {
                $output->writeln('<error>' . $exception->getMessage() . '</error>');

                return;
            }

            $envPath = __DIR__ . '/../../.env';
            $parameter = 'JWT_PASSPHRASE';
            $envBody = file_get_contents($envPath);
            $envBody = preg_replace('/.*\b' . $parameter . '\b.*\n/ui', "$parameter=$passPhrase\n", $envBody);
            file_put_contents($envPath, $envBody);

            $output->writeln('<info>Success.</info>');

            return;
        }

        $output->writeln('<info>Already generated.</info>');
    }
}
