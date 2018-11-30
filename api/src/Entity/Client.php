<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"layout:public", "layout:read"}},
 *     denormalizationContext={"groups": {"layout:public", "layout:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\Table(name="clients",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="company_email_unique", columns={"company_id", "email"}),
 *        @UniqueConstraint(name="company_phone_unique", columns={"company_id", "phone"})
 *    }
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"layout:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"layout:public"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"layout:public"})
     */
    private $email;

    /**
     * @ORM\Column(type="phone_number")
     * @Groups({"layout:public"})
     */
    private $phone;

    /**
     * @ORM\Column(type="text")
     * @Groups({"layout:public"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="client")
     * @Groups({"layout:read"})
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Request", mappedBy="client")
     * @Groups({"layout:read"})
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="clients")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"layout:read"})
     */
    private $company;

    public function __construct()
    {
        $this->project = new ArrayCollection();
        $this->request = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Client
     *
     * @throws \libphonenumber\NumberParseException
     */
    public function setPhone($phone): self
    {
        if (is_string($phone)) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $phone = $phoneNumberUtil->parse($phone, PhoneNumberUtil::UNKNOWN_REGION);
        }

        $this->phone = $phone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Project $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
            $project->setClient($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->project->contains($project)) {
            $this->project->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getClient() === $this) {
                $project->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Request[]
     */
    public function getRequest(): Collection
    {
        return $this->request;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->request->contains($request)) {
            $this->request[] = $request;
            $request->setClient($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->request->contains($request)) {
            $this->request->removeElement($request);
            // set the owning side to null (unless already changed)
            if ($request->getClient() === $this) {
                $request->setClient(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
